<?php

namespace App\Entity;

use App\Repository\ConfirmationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConfirmationRepository::class)
 */
class Confirmation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $CredConf;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="confirmation", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $CredToken;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $credTime;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCredConf(): ?bool
    {
        return $this->CredConf;
    }

    public function setCredConf(?bool $CredConf): self
    {
        $this->CredConf = $CredConf;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCredToken(): ?string
    {
        return $this->CredToken;
    }

    public function setCredToken(?string $CredToken): self
    {
        $this->CredToken = $CredToken;

        return $this;
    }

    public function getCredTime(): ?\DateTimeInterface
    {
        return $this->credTime;
    }

    public function setCredTime(?\DateTimeInterface $credTime): self
    {
        $this->credTime = $credTime;

        return $this;
    }
}
