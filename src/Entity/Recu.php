<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Recu
 *
 * @ORM\Table(name="recu")
 * @ORM\Entity
 */
class Recu
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name_client", type="string", length=30, nullable=false)
     */
    private $nameClient;

    /**
     * @var float
     *
     * @ORM\Column(name="impot", type="float", precision=10, scale=0, nullable=false)
     */
    private $impot;

    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float", precision=10, scale=0, nullable=false)
     */
    private $total;

    /**
     * @var float
     *
     * @ORM\Column(name="mtotal", type="float", precision=10, scale=0, nullable=false)
     */
    private $mtotal;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $date = 'CURRENT_TIMESTAMP';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameClient(): ?string
    {
        return $this->nameClient;
    }

    public function setNameClient(string $nameClient): static
    {
        $this->nameClient = $nameClient;

        return $this;
    }

    public function getImpot(): ?float
    {
        return $this->impot;
    }

    public function setImpot(float $impot): static
    {
        $this->impot = $impot;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getMtotal(): ?float
    {
        return $this->mtotal;
    }

    public function setMtotal(float $mtotal): static
    {
        $this->mtotal = $mtotal;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }


}
