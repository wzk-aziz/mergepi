<?php

namespace App\Entity;

use Assert\GreaterThanOrEqual;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\InventoryRepository;

use Doctrine\Common\Collections\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;


#[ORM\Entity(repositoryClass: InventoryRepository::class)]
class Inventory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[NotBlank(message: "le titre ne doit pas être vide")]
    #[Length(max: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 1023)]
    #[NotBlank]
    #[Length(max: 1023)]
    private ?string $description = null;

    #[ORM\Column(type: "datetime")]
    #[NotBlank(message: "La date d'ajout est requise")]
    #[EqualTo("today", message: "La date d'ajout doit être égale à la date d'ajourd'hui")]
    #[Type("\DateTimeInterface")]
    private ?\DateTimeInterface $add_date = null;

    #[ORM\OneToMany(mappedBy: 'Inventory', targetEntity: Items::class)]
    private Collection $items;

    #[ORM\ManyToOne(inversedBy: 'inventories')]
    private ?User $User = null;



    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getAddDate(): ?\DateTimeInterface
    {
        return $this->add_date;
    }

    public function setAddDate(\DateTimeInterface $add_date): static
    {
        $this->add_date = $add_date;

        return $this;
    }

    #[ORM\PrePersist]
    public function setAddDateAutomatically(): void
    {
        $this->add_date = new \DateTime();
    }


    /**
     * @return Collection<int, Items>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Items $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setInventory($this);
        }

        return $this;
    }

    public function removeItem(Items $item): static
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getInventory() === $this) {
                $item->setInventory(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->title;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): static
    {
        $this->User = $User;

        return $this;
    }
}
