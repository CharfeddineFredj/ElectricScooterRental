<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $model = null;

    #[ORM\Column(nullable: true)]
    private ?int $qte = null;

    #[ORM\Column(nullable: true)]
    private ?int $prix = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $datersv = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $heurersv = null;

    #[ORM\Column(nullable: true)]
    private ?int $periode = null;

    #[ORM\Column(nullable: true)]
    private ?float $impot = null;

    #[ORM\Column(nullable: true)]
    private ?float $total = null;

    #[ORM\Column(nullable: true)]
    private ?float $mtotal = null;
    
    #[ORM\Column(length: 25, nullable: true)]
    private ?string $status = null;

    #[ORM\OneToOne(targetEntity: Offer::class, mappedBy: "reservation")]
    private ?Offer $offer = null;


    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;
        return $this;
    }
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(?string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getQte(): ?int
    {
        return $this->qte;
    }

    public function setQte(?int $qte): static
    {
        $this->qte = $qte;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(?int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDatersv(): ?\DateTimeInterface
    {
        return $this->datersv ? new \DateTime($this->datersv) : null;
    }

    public function setDatersv(?\DateTimeInterface $datersv): self
    {
        $this->datersv = $datersv ? $datersv->format('Y-m-d') : null;
        return $this;
    }

    public function getHeurersv(): ?string
    {
        return $this->heurersv;
    }

    public function setHeurersv(?string $heurersv): static
    {
        $this->heurersv = $heurersv;

        return $this;
    }

    public function getPeriode(): ?int
    {
        return $this->periode;
    }

    public function setPeriode(?int $periode): static
    {
        $this->periode = $periode;

        return $this;
    }

    public function getImpot(): ?float
    {
        return $this->impot;
    }

    public function setImpot(?float $impot): static
    {
        $this->impot = $impot;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(?float $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getMtotal(): ?float
    {
        return $this->mtotal;
    }

    public function setMtotal(?float $mtotal): static
    {
        $this->mtotal = $mtotal;

        return $this;
    }

    public function getOffer(): ?Offer
    {
        return $this->offer;
    }

    public function setOffer(?Offer $offer): self
    {
        $this->offer = $offer;
        if ($offer !== null && $offer->getReservation() !== $this) {
            $offer->setReservation($this);
        }
        return $this;
    }

    public function getOfferStatus(): ?string
        {
            return $this->offer ? $this->offer->getStatusOffre() : null;
        }
}
