<?php
declare(strict_types=1);

namespace App\Entity;

class Etudiant
{
    private $id;
    private $cne;
    private $nom;
    private $prenom;
    private $email;
    private $filiereId;

    public function __construct(?int $id, string $cne, string $nom, string $prenom, string $email, int $filiereId)
    {
        $this->id = $id;
        $this->cne = $cne;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->filiereId = $filiereId;
    }

    public function getId(): ?int { return $this->id; }
    public function getCne(): string { return $this->cne; }
    public function getNom(): string { return $this->nom; }
    public function getPrenom(): string { return $this->prenom; }
    public function getEmail(): string { return $this->email; }
    public function getFiliereId(): int { return $this->filiereId; }
}