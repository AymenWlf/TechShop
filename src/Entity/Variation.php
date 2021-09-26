<?php

namespace App\Entity;

use App\Repository\VariationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Query\Expr\Func;

/**
 * @ORM\Entity(repositoryClass=VariationRepository::class)
 */
class Variation
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
    private $illustration;

    /**
     * @ORM\OneToMany(targetEntity=VariationOption::class, mappedBy="variation")
     */
    private $variationOptions;

    public function __construct()
    {
        $this->variationOptions = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

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

    public function getIllustration(): ?string
    {
        return $this->illustration;
    }

    public function setIllustration(string $illustration): self
    {
        $this->illustration = $illustration;

        return $this;
    }

    /**
     * @return Collection|VariationOption[]
     */
    public function getVariationOptions(): Collection
    {
        return $this->variationOptions;
    }

    public function addVariationOption(VariationOption $variationOption): self
    {
        if (!$this->variationOptions->contains($variationOption)) {
            $this->variationOptions[] = $variationOption;
            $variationOption->setVariation($this);
        }

        return $this;
    }

    public function removeVariationOption(VariationOption $variationOption): self
    {
        if ($this->variationOptions->removeElement($variationOption)) {
            // set the owning side to null (unless already changed)
            if ($variationOption->getVariation() === $this) {
                $variationOption->setVariation(null);
            }
        }

        return $this;
    }
}
