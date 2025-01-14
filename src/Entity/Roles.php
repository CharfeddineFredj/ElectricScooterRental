<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "roles")]
class Roles
{
    #[ORM\Id]
    #[ORM\Column(type: "integer", nullable: false)]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private $id;

    #[ORM\Column(type: "string", length: 50, nullable: true)]
    private $roleName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoleName(): ?string
    {
        return $this->roleName;
    }
    public function __toString()
    {
        // Retournez ici une chaîne de caractères qui représente l'objet, par exemple le nom du rôle
        return $this->roleName;
    }
    public function setRoleName(?string $roleName): self
    {
        $this->roleName = $roleName;

        return $this;
    }
}
