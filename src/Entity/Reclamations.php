<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reclamations
 *
 * @ORM\Table(name="reclamations")
 * @ORM\Entity
 */
class Reclamations
{
    /**
     * @var int
     *
     * @ORM\Column(name="idrec", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idrec;

    /**
     * @var string
     *
     * @ORM\Column(name="fullname", type="string", length=250, nullable=false)
     */
    private $fullname;

    /**
     * @var string
     *
     * @ORM\Column(name="emailrec", type="string", length=250, nullable=false)
     */
    private $emailrec;

    /**
     * @var int
     *
     * @ORM\Column(name="pnrec", type="integer", nullable=false)
     */
    private $pnrec;

    /**
     * @var string
     *
     * @ORM\Column(name="issue", type="string", length=250, nullable=false)
     */
    private $issue;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=250, nullable=false)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=250, nullable=false)
     */
    private $message;

    /**
     * @var int
     *
     * @ORM\Column(name="stater", type="integer", nullable=false)
     */
    private $stater = '0';

    public function getIdrec(): ?int
    {
        return $this->idrec;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): static
    {
        $this->fullname = $fullname;

        return $this;
    }

    public function getEmailrec(): ?string
    {
        return $this->emailrec;
    }

    public function setEmailrec(string $emailrec): static
    {
        $this->emailrec = $emailrec;

        return $this;
    }

    public function getPnrec(): ?int
    {
        return $this->pnrec;
    }

    public function setPnrec(int $pnrec): static
    {
        $this->pnrec = $pnrec;

        return $this;
    }

    public function getIssue(): ?string
    {
        return $this->issue;
    }

    public function setIssue(string $issue): static
    {
        $this->issue = $issue;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getStater(): ?int
    {
        return $this->stater;
    }

    public function setStater(int $stater): static
    {
        $this->stater = $stater;

        return $this;
    }


}
