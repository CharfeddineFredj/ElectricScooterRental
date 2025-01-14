<?php

namespace App\Entity;

use App\Entity\Station;
use App\Repository\TrotinetteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: TrotinetteRepository::class)]

class Trotinette
{   #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idTrotinette;
   

  
    #[ORM\Column]
    private ?int $vitesse=null;
   
    

    #[ORM\Column]
    private ?int $charge=null;


    
    #[ORM\Column(length: 250)]
    private ?string $couleur=null;

    
    #[ORM\Column(length: 250)]
    private ?string $dispo=null;

   
    #[ORM\ManyToOne(targetEntity: Station::class)]
    #[ORM\JoinColumn(name: "id_station", referencedColumnName: "id_station")]
    private $idStation;

    public function getIdTrotinette(): ?int
    {
        return $this->idTrotinette;
    }

    public function getVitesse(): ?int
    {
        return $this->vitesse;
    }

    public function setVitesse(int $vitesse): static
    {
        $this->vitesse = $vitesse;

        return $this;
    }

    public function getCharge(): ?int
    {
        return $this->charge;
    }

    public function setCharge(int $charge): static
    {
        $this->charge = $charge;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): static
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getDispo(): ?string
    {
        return $this->dispo;
    }

    public function setDispo(string $dispo): static
    {
        $this->dispo = $dispo;

        return $this;
    }

    public function getIdStation(): ?Station
    {
        return $this->idStation;
    }

    public function setIdStation(?Station $idStation): static
    {
        $this->idStation = $idStation;

        return $this;
    }


}
