<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AddressRepository::class)
 */
class Address
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *          min = 3,
     *          max = 40,
     *          minMessage = "Le nom de votre adresse doit etre plus grand que {{ limit }}",
     *          maxMessage = "Le nom de votre adresse doit etre plus petit que {{ limit }}"
     * )
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="addresses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *          min = 3,
     *          max = 40,
     *          minMessage = "Votre prénom doit etre plus grand que {{ limit }}",
     *          maxMessage = "Votre prénom doit etre plus petit que {{ limit }}"
     * )
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *          min = 3,
     *          max = 40,
     *          minMessage = "Votre nom de votre adresse doit etre plus grand que {{ limit }}",
     *          maxMessage = "Votre nom de votre adresse doit etre plus petit que {{ limit }}"
     * )
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *          min = 3,
     *          max = 60,
     *          minMessage = "Le nom de votre entreprise doit etre plus grand que {{ limit }}",
     *          maxMessage = "Le nom de votre entreprise doit etre plus petit que {{ limit }}"
     * )
     */
    private $company;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(
     *          min = 10,
     *          max = 60,
     *          minMessage = "Votre adresse doit etre plus grand que {{ limit }}",
     *          maxMessage = "Votre adresse doit etre plus petit que {{ limit }}"
     * )
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *          min = 3,
     *          max = 15,
     *          minMessage = "Votre Code Postal doit etre plus grand que {{ limit }}",
     *          maxMessage = "Votre Code Postal doit etre plus petit que {{ limit }}"
     * )
     */
    private $postal;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *          min = 2,
     *          max = 20,
     *          minMessage = "Le nom de votre ville doit etre plus grand que {{ limit }}",
     *          maxMessage = "Le nom de votre ville doit etre plus petit que {{ limit }}"
     * )
     */
    private $city;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Length(
     *          min = 8,
     *          minMessage = "Votre Numero doit etre plus grand que {{ limit }}"
     * )
     */
    private $phone;

    // public function __toString()
    // {
    //     return $this->name.'-'.$this->address.'-'.$this->country.'-'.$this->city.'-'.$this->phone;
    // }
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPostal(): ?string
    {
        return $this->postal;
    }

    public function setPostal(string $postal): self
    {
        $this->postal = $postal;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

}
