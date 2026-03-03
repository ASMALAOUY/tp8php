<?php
namespace App\Dao;

use PDO;

class FiliereDao
{
    private $pdo;
    private $logger;

    public function __construct(PDO $pdo, Logger $logger)
    {
        $this->pdo = $pdo;
        $this->logger = $logger;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT id, code, libelle FROM filiere ORDER BY code');
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id, code, libelle FROM filiere WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
}