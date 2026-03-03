<?php
namespace App\Controller;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Dao\EtudiantDao;
use App\Dao\FiliereDao;
use App\Security\Sanitizer;
use App\Security\Validator;

class EtudiantController extends BaseController
{
    private $etudiantDao;
    private $filiereDao;

    public function __construct(View $view, Response $response, EtudiantDao $etudiantDao, FiliereDao $filiereDao)
    {
        parent::__construct($view, $response);
        $this->etudiantDao = $etudiantDao;
        $this->filiereDao = $filiereDao;
    }

    public function index(Request $request): void
    {
        $q = Sanitizer::string($request->getQueryParam('q', ''), 100);
        $filiereId = (int)$request->getQueryParam('filiere_id', 0);
        $page = (int)$request->getQueryParam('page', 1);
        $size = (int)$request->getQueryParam('size', 5);

        $total = $this->etudiantDao->countSearch($q, $filiereId ?: null);
        $items = $this->etudiantDao->searchPaginated($q, $filiereId ?: null, $page, $size);
        $totalPages = max(1, (int)ceil($total / max(1, $size)));

        $filieres = $this->filiereDao->findAll();

        $this->render('etudiant/index.php', [
            'etudiants' => $items,
            'q' => $q,
            'filiereId' => $filiereId,
            'filieres' => $filieres,
            'page' => max(1, $page),
            'size' => max(1, $size),
            'total' => $total,
            'totalPages' => $totalPages,
        ]);
    }

    public function show(Request $request, array $params): void
    {
        $id = (int)($params['id'] ?? 0);
        $etudiant = $this->etudiantDao->findById($id);
        
        if (!$etudiant) {
            $this->redirect('/etudiants');
            return;
        }
        
        $this->render('etudiant/show.php', [
            'e' => $etudiant
        ]);
    }

    public function create(Request $request): void
    {
        $filieres = $this->filiereDao->findAll();
        $this->render('etudiant/create.php', [
            'filieres' => $filieres,
            'errors' => [],
            'old' => []
        ]);
    }

    public function store(Request $request): void
    {
        $data = $this->sanitize($request->getBodyParams());
        $errors = $this->validate($data);
        
        if (!empty($errors)) {
            $filieres = $this->filiereDao->findAll();
            $this->render('etudiant/create.php', [
                'filieres' => $filieres,
                'errors' => $errors,
                'old' => $data
            ]);
            return;
        }
        
        $id = $this->etudiantDao->create($data);
        $this->redirect('/etudiants/' . $id);
    }

    public function edit(Request $request, array $params): void
    {
        $id = (int)($params['id'] ?? 0);
        $etudiant = $this->etudiantDao->findById($id);
        
        if (!$etudiant) {
            $this->redirect('/etudiants');
            return;
        }
        
        $filieres = $this->filiereDao->findAll();
        $this->render('etudiant/edit.php', [
            'etudiant' => $etudiant,
            'filieres' => $filieres,
            'errors' => [],
            'old' => []
        ]);
    }

    public function update(Request $request, array $params): void
    {
        $id = (int)($params['id'] ?? 0);
        $etudiant = $this->etudiantDao->findById($id);
        
        if (!$etudiant) {
            $this->redirect('/etudiants');
            return;
        }
        
        $data = $this->sanitize($request->getBodyParams());
        $errors = $this->validate($data, $id);
        
        if (!empty($errors)) {
            $filieres = $this->filiereDao->findAll();
            $this->render('etudiant/edit.php', [
                'etudiant' => $etudiant,
                'filieres' => $filieres,
                'errors' => $errors,
                'old' => $data
            ]);
            return;
        }
        
        $this->etudiantDao->update($id, $data);
        $this->redirect('/etudiants/' . $id);
    }

    public function delete(Request $request, array $params): void
    {
        $id = (int)($params['id'] ?? 0);
        $this->etudiantDao->delete($id);
        $this->redirect('/etudiants');
    }

    public function apiList(Request $request): void
    {
        $q = Sanitizer::string($request->getQueryParam('q', ''), 100);
        $filiereId = (int)$request->getQueryParam('filiere_id', 0);
        $page = (int)$request->getQueryParam('page', 1);
        $size = (int)$request->getQueryParam('size', 20);

        $items = $this->etudiantDao->searchPaginated($q, $filiereId ?: null, $page, $size);
        $this->json($items);
    }

    private function sanitize(array $data): array
    {
        $data = Sanitizer::trimArray($data);
        return [
            'cne' => strtoupper(Sanitizer::string($data['cne'] ?? '', 20)),
            'nom' => Sanitizer::string($data['nom'] ?? '', 50),
            'prenom' => Sanitizer::string($data['prenom'] ?? '', 50),
            'email' => Sanitizer::email($data['email'] ?? ''),
            'filiere_id' => (int)($data['filiere_id'] ?? 0),
        ];
    }

    private function validate(array $data, ?int $id = null): array
    {
        $errors = [];
        if (!Validator::cne($data['cne'])) { 
            $errors['cne'] = 'CNE requis (A-Z, 0-9, 6-20).'; 
        }
        if ($data['nom'] === '' || !Validator::maxLen($data['nom'], 50)) { 
            $errors['nom'] = 'Nom requis (<=50).'; 
        }
        if ($data['prenom'] === '' || !Validator::maxLen($data['prenom'], 50)) { 
            $errors['prenom'] = 'Prénom requis (<=50).'; 
        }
        if (!Validator::email($data['email']) || !Validator::maxLen($data['email'], 100)) { 
            $errors['email'] = 'Email invalide (<=100).'; 
        }
        if ($data['filiere_id'] <= 0 || !$this->filiereDao->findById((int)$data['filiere_id'])) { 
            $errors['filiere_id'] = 'Filière invalide.'; 
        }
        return $errors;
    }
}