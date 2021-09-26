<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
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
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $illustration;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $subtitle;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="products")
     */
    private $category;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isBest;

    /**
     * @ORM\ManyToMany(targetEntity=WishList::class, mappedBy="products")
     */
    private $wishLists;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $illustration2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $illustration3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $illustration4;

    /**
     * @ORM\OneToMany(targetEntity=VariationOption::class, mappedBy="product")
     */
    private $variationOptions;

    
   

    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->wishLists = new ArrayCollection();
        $this->illustrations = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(string $subtitle): self
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategory(): Collection
    {
        return $this->category;

    }

    public function addCategory(Category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->category->removeElement($category);

        return $this;
    }

    public function getIsBest(): ?bool
    {
        return $this->isBest;
    }

    public function setIsBest(bool $isBest): self
    {
        $this->isBest = $isBest;

        return $this;
    }

    /**
     * @return Collection|WishList[]
     */
    public function getWishLists(): Collection
    {
        return $this->wishLists;
    }

    public function addWishList(WishList $wishList): self
    {
        if (!$this->wishLists->contains($wishList)) {
            $this->wishLists[] = $wishList;
            $wishList->addProduct($this);
        }

        return $this;
    }

    public function removeWishList(WishList $wishList): self
    {
        if ($this->wishLists->removeElement($wishList)) {
            $wishList->removeProduct($this);
        }

        return $this;
    }

    public function getIllustration2(): ?string
    {
        return $this->illustration2;
    }

    public function setIllustration2(?string $illustration2): self
    {
        $this->illustration2 = $illustration2;

        return $this;
    }

    public function getIllustration3(): ?string
    {
        return $this->illustration3;
    }

    public function setIllustration3(?string $illustration3): self
    {
        $this->illustration3 = $illustration3;

        return $this;
    }

    public function getIllustration4(): ?string
    {
        return $this->illustration4;
    }

    public function setIllustration4(?string $illustration4): self
    {
        $this->illustration4 = $illustration4;

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
            $variationOption->setProduct($this);
        }

        return $this;
    }

    public function removeVariationOption(VariationOption $variationOption): self
    {
        if ($this->variationOptions->removeElement($variationOption)) {
            // set the owning side to null (unless already changed)
            if ($variationOption->getProduct() === $this) {
                $variationOption->setProduct(null);
            }
        }

        return $this;
    }

}