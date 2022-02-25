<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 */
class Customer
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
    private $firstName;

    /**
     * @ORM\Column(type="string", length=250)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=230, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=250)
     */
    private $phoneNumber;

    /**
     * @ORM\OneToMany(targetEntity=Orders::class, mappedBy="customer_id", orphanRemoval=true)
     */
    private $order;

    public function __construct()
    {
        $this->order = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

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

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }


    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'firstName' => $this->getFirstName(),
            'lastName' => $this->getLastName(),
            'email' => $this->getEmail(),
            'phoneNumber' => $this->getPhoneNumber()
        ];
    }

    /**
     * @return Collection<int, Orders>
     */
    public function getOrder(): Collection
    {
        return $this->order;
    }

    public function addOrder(Orders $order): self
    {
        if (!$this->order->contains($order)) {
            $this->order[] = $order;
            $order->setCustomerId($this);
        }

        return $this;
    }

    public function removeOrder(Orders $order): self
    {
        if ($this->order->removeElement($order)) {
            if ($order->getCustomerId() === $this) {
                $order->setCustomerId(null);
            }
        }

        return $this;
    }
}
