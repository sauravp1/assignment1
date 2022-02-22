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
    private $prod;

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
    public function getProd(): Collection
    {
        return $this->prod;
    }

    public function addProd(Orders $prod): self
    {
        if (!$this->prod->contains($prod)) {
            $this->prod[] = $prod;
            $prod->setProductId($this);
        }

        return $this;
    }

    public function removeProd(Orders $prod): self
    {
        if ($this->prod->removeElement($prod)) {
            // set the owning side to null (unless already changed)
            if ($prod->getProductId() === $this) {
                $prod->setProductId(null);
            }
        }

        return $this;
    }
}
