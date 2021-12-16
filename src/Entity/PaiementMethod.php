<?php

namespace App\Entity;

use App\Repository\PaiementMethodRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaiementMethodRepository::class)
 */
class PaiementMethod
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
     * @ORM\Column(type="integer")
     */
    private $value;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="paiementMethod")
     */
    private $myOrder;


    public function __construct()
    {
        $this->myOrder = new ArrayCollection();
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

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getMyOrder(): Collection
    {
        return $this->myOrder;
    }

    public function addMyOrder(Order $myOrder): self
    {
        if (!$this->myOrder->contains($myOrder)) {
            $this->myOrder[] = $myOrder;
            $myOrder->setPaiementMethod($this);
        }

        return $this;
    }

    public function removeMyOrder(Order $myOrder): self
    {
        if ($this->myOrder->removeElement($myOrder)) {
            // set the owning side to null (unless already changed)
            if ($myOrder->getPaiementMethod() === $this) {
                $myOrder->setPaiementMethod(null);
            }
        }

        return $this;
    }

}
