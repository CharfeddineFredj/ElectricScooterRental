<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Reponse
 *
 * @ORM\Table(name="reponse", indexes={@ORM\Index(name="fk", columns={"idrec"})})
 * @ORM\Entity
 */
class Reponse
{
    /**
     * @var int
     *
     * @ORM\Column(name="idrep", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idrep;

    /**
     * @var string
     *
     * @ORM\Column(name="emailrep", type="string", length=250, nullable=false)
     */
    private $emailrep;

    /**
     * @var string
     *
     * @ORM\Column(name="response", type="string", length=200, nullable=false)
     */
    private $response;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="daterep", type="date", nullable=false)
     */
    private $daterep;

    /**
     * @var int
     *
     * @ORM\Column(name="idrec", type="integer", nullable=false)
     */
    private $idrec;

    public function getIdrep(): ?int
    {
        return $this->idrep;
    }

    public function getEmailrep(): ?string
    {
        return $this->emailrep;
    }

    public function setEmailrep(string $emailrep): static
    {
        $this->emailrep = $emailrep;

        return $this;
    }

    public function getResponse(): ?string
    {
        return $this->response;
    }

    public function setResponse(string $response): static
    {
        $this->response = $response;

        return $this;
    }

    public function getDaterep(): ?\DateTimeInterface
    {
        return $this->daterep;
    }

    public function setDaterep(\DateTimeInterface $daterep): static
    {
        $this->daterep = $daterep;

        return $this;
    }

    public function getIdrec(): ?int
    {
        return $this->idrec;
    }

    public function setIdrec(int $idrec): static
    {
        $this->idrec = $idrec;

        return $this;
    }


}
