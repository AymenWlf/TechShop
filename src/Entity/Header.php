<?php

namespace App\Entity;

use App\Repository\HeaderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HeaderRepository::class)
 */
class Header
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $topCmnt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $middleCmnt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastCmnt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $btnTitle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $illustration;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTopCmnt(): ?string
    {
        return $this->topCmnt;
    }

    public function setTopCmnt(?string $topCmnt): self
    {
        $this->topCmnt = $topCmnt;

        return $this;
    }

    public function getMiddleCmnt(): ?string
    {
        return $this->middleCmnt;
    }

    public function setMiddleCmnt(?string $middleCmnt): self
    {
        $this->middleCmnt = $middleCmnt;

        return $this;
    }

    public function getLastCmnt(): ?string
    {
        return $this->lastCmnt;
    }

    public function setLastCmnt(?string $lastCmnt): self
    {
        $this->lastCmnt = $lastCmnt;

        return $this;
    }

    public function getBtnTitle(): ?string
    {
        return $this->btnTitle;
    }

    public function setBtnTitle(string $btnTitle): self
    {
        $this->btnTitle = $btnTitle;

        return $this;
    }

    public function getIllustration(): ?string
    {
        return $this->illustration;
    }

    public function setIllustration(string $illustration): self
    {
        $this->illustration = $illustration;

        return $this;
    }
}
