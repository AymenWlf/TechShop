<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 *              fields = "email",
 *              message = "Cet email est dejà utilisée"
 * 
 * )
 * 
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Length(
     *          min = 10,
     *          max = 40,
     *          minMessage = "L'email doit etre plus grand que {{ limit }}",
     *          maxMessage = "L'email doit etre plus petit que {{ limit }}"
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\Length(
     *          min = 3,
     *          max = 20,
     *          minMessage = "Le mot de passe doit etre plus grand que {{ limit }}",
     *          maxMessage = "Le mot de passe doit etre plus petit que {{ limit }}"
     * )
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)*
     * @Assert\Length(
     *          min = 3,
     *          max = 20,
     *          minMessage = "Le prénom doit etre plus grand que {{ limit }}",
     *          maxMessage = "Le prénom doit etre plus petit que {{ limit }}"
     * )
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *          min = 3,
     *          max = 20,
     *          minMessage = "Le pseudoname doit etre plus grand que {{ limit }}",
     *          maxMessage = "Le pseudoname doit etre plus petit que {{ limit }}"
     * )
     */
    private $pseudoname;

    /**
     * @ORM\OneToOne(targetEntity=WishList::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $wishList;

    /**
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="user")
     */
    private $reviews;

    /**
     * @ORM\OneToMany(targetEntity=CartItem::class, mappedBy="user")
     */
    private $cartItems;

    /**
     * @ORM\OneToMany(targetEntity=Address::class, mappedBy="user")
     */
    private $addresses;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="user")
     */
    private $orders;

    /**
     * @ORM\OneToMany(targetEntity=Contact::class, mappedBy="user")
     */
    private $contacts;

    /**
     * @ORM\OneToOne(targetEntity=Confirmation::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $confirmation;

    /**
     * @ORM\OneToMany(targetEntity=ModifPassword::class, mappedBy="user")
     */
    private $modifPasswords;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
        $this->cartItems = new ArrayCollection();
        $this->addresses = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->contacts = new ArrayCollection();
        $this->modifPasswords = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->pseudoname;
    }


    public function getId(): ?int
    {
        return $this->id;
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
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getPseudoname(): ?string
    {
        return $this->pseudoname;
    }

    public function setPseudoname(string $pseudoname): self
    {
        $this->pseudoname = $pseudoname;

        return $this;
    }

    public function getWishList(): ?WishList
    {
        return $this->wishList;
    }

    public function setWishList(WishList $wishList): self
    {
        // set the owning side of the relation if necessary
        if ($wishList->getUser() !== $this) {
            $wishList->setUser($this);
        }

        $this->wishList = $wishList;

        return $this;
    }

    /**
     * @return Collection|Review[]
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setUser($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getUser() === $this) {
                $review->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CartItem[]
     */
    public function getCartItems(): Collection
    {
        return $this->cartItems;
    }

    public function addCartItem(CartItem $cartItem): self
    {
        if (!$this->cartItems->contains($cartItem)) {
            $this->cartItems[] = $cartItem;
            $cartItem->setUser($this);
        }

        return $this;
    }

    public function removeCartItem(CartItem $cartItem): self
    {
        if ($this->cartItems->removeElement($cartItem)) {
            // set the owning side to null (unless already changed)
            if ($cartItem->getUser() === $this) {
                $cartItem->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Address[]
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Address $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses[] = $address;
            $address->setUser($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        if ($this->addresses->removeElement($address)) {
            // set the owning side to null (unless already changed)
            if ($address->getUser() === $this) {
                $address->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setUser($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getUser() === $this) {
                $order->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Contact[]
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts[] = $contact;
            $contact->setUser($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): self
    {
        if ($this->contacts->removeElement($contact)) {
            // set the owning side to null (unless already changed)
            if ($contact->getUser() === $this) {
                $contact->setUser(null);
            }
        }

        return $this;
    }

    public function getConfirmation(): ?Confirmation
    {
        return $this->confirmation;
    }

    public function setConfirmation(Confirmation $confirmation): self
    {
        // set the owning side of the relation if necessary
        if ($confirmation->getUser() !== $this) {
            $confirmation->setUser($this);
        }

        $this->confirmation = $confirmation;

        return $this;
    }

    /**
     * @return Collection|ModifPassword[]
     */
    public function getModifPasswords(): Collection
    {
        return $this->modifPasswords;
    }

    public function addModifPassword(ModifPassword $modifPassword): self
    {
        if (!$this->modifPasswords->contains($modifPassword)) {
            $this->modifPasswords[] = $modifPassword;
            $modifPassword->setUser($this);
        }

        return $this;
    }

    public function removeModifPassword(ModifPassword $modifPassword): self
    {
        if ($this->modifPasswords->removeElement($modifPassword)) {
            // set the owning side to null (unless already changed)
            if ($modifPassword->getUser() === $this) {
                $modifPassword->setUser(null);
            }
        }

        return $this;
    }
}
