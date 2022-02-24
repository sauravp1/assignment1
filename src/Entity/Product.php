<?php

namespace App\Entity;

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
     * @ORM\Column(type="string", length=250)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=250)
     */
    private $category;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity=Orders::class, mappedBy="product_id", orphanRemoval=true)
     */
    private $product;

    public function __construct()
    {
        $this->prod = new ArrayCollection();
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

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, Orders>
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(Orders $product): self
    {
        if (!$this->product->contains($product)) {
            $this->product[] = $product;
            $product->setProductId($this);
        }

        return $this;
    }

    public function removeProduct(Orders $product): self
    {
        if ($this->prod->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getProductId() === $this) {
                $product->setProductId(null);
            }
        }

        return $this;
    }
}
