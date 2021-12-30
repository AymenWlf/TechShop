<?php

namespace App\Entity;

use App\Repository\SubscriberRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SubscriberRepository::class)
 */
class Subscriber
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="subscribers")
     */
    private $user;


    /**
     * @ORM\Column(type="string", length=50)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity=PromoCode::class, mappedBy="subscriber")
     */
    private $promocode;

    public function __construct()
    {
        $this->promocode = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

     public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }


    /**
     * @return Collection|PromoCode[]
     */
    public function getPromocode(): Collection
    {
        return $this->promocode;
    }

    public function addPromocode(PromoCode $promocode): self
    {
        if (!$this->promocode->contains($promocode)) {
            $this->promocode[] = $promocode;
            $promocode->setSubscriber($this);
        }

        return $this;
    }

    public function removePromocode(PromoCode $promocode): self
    {
        if ($this->promocode->removeElement($promocode)) {
            // set the owning side to null (unless already changed)
            if ($promocode->getSubscriber() === $this) {
                $promocode->setSubscriber(null);
            }
        }

        return $this;
    }
}
