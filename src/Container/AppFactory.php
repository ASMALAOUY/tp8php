<?php
namespace App\Container;

use App\Core\Request;
use App\Core\Response;
use App\Core\Router;
use App\Core\View;
use App\Controller\EtudiantController;
use App\Controller\AuthController;
use App\Dao\DBConnection;
use App\Dao\Logger;
use App\Dao\EtudiantDao;
use App\Dao\FiliereDao;
use App\Dao\AdminDao;
use App\Security\Auth;
use App\Security\Csrf;
use App\Security\Middleware;

class AppFactory
{
    public function create(): array
    {
        // Config DB
        $dbHost = '127.0.0.1'; $dbName = 'gestion_etudiants_pdo'; $dbUser = 'root'; $dbPass = ''; $charset = 'utf8mb4';
        $logger = new Logger(__DIR__ . '/../../logs/app.log');
        $pdo = DBConnection::create($dbHost, $dbName, $dbUser, $dbPass, $charset, $logger);

        // Services
        $adminDao = new AdminDao($pdo, $logger);
        $auth = new Auth($adminDao); $auth->startSession();
        $csrf = new Csrf(); $csrf->token(); // initialise le token si absent
        $mw = new Middleware($auth, $csrf);

        // MVC
        $etudiantDao = new EtudiantDao($pdo, $logger);
        $filiereDao = new FiliereDao($pdo, $logger);
        $view = new View(__DIR__ . '/../../views');
        $response = new Response();
        $request = new Request();
        $router = new Router();

        // Controllers
        $etudiantController = new EtudiantController($view, $response, $etudiantDao, $filiereDao);
        $authController = new AuthController($view, $response, $auth, $csrf);

        // Routes publiques
        $router->get('/', function() use ($response) { $response->redirect('/etudiants'); });
        $router->get('/login', [$authController, 'loginForm']);
        $router->post('/login', $mw->requireCsrfPost([$authController, 'login']));

        // Logout (POST + CSRF)
        $router->post('/logout', $mw->requireCsrfPost([$authController, 'logout']));

        // Listing public (lecture)
        $router->get('/etudiants', [$etudiantController, 'index']);
        $router->get('/etudiants/{id}', [$etudiantController, 'show']);

        // Routes protégées admin
        $router->get('/etudiants/create', $mw->requireAdmin([$etudiantController, 'create']));
        $router->post('/etudiants/store', $mw->requireAdmin($mw->requireCsrfPost([$etudiantController, 'store'])));
        $router->get('/etudiants/{id}/edit', $mw->requireAdmin([$etudiantController, 'edit']));
        $router->post('/etudiants/{id}/update', $mw->requireAdmin($mw->requireCsrfPost([$etudiantController, 'update'])));
        $router->post('/etudiants/{id}/delete', $mw->requireAdmin($mw->requireCsrfPost([$etudiantController, 'delete'])));

        // Optionnel API (lecture publique)
        $router->get('/api/etudiants', [$etudiantController, 'apiList']);

        return [$router, $request];
    }
}