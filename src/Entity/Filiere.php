<?php
declare(strict_types=1);

namespace App\Entity;

class Filiere
{
    private $id;
    private $code;
    private $libelle;

    public function __construct(?int $id, string $code, string $libelle)
    {
        $this->id = $id;
        $this->code = $code;
        $this->libelle = $libelle;
    }

    public function getId(): ?int { return $this->id; }
    public function getCode(): string { return $this->code; }
    public function getLibelle(): string { return $this->libelle; }
}