<?php

namespace App\Entity;

use App\Repository\ItemsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: ItemsRepository::class)]
class Items
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[NotBlank(message: "Name cannot be blank.")]
    #[Length(max: 255, maxMessage: "Name cannot be longer than {{ limit }} characters.")]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Length(max: 255, maxMessage: "Description cannot be longer than {{ limit }} characters.")]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[NotBlank(message: "Reference cannot be blank.")]
    #[Length(max: 255, maxMessage: "Reference cannot be longer than {{ limit }} characters.")]
    private ?string $ref = null;

    #[ORM\Column(length: 255)]
    #[Length(max: 255, maxMessage: "Part condition cannot be longer than {{ limit }} characters.")]
    private ?string $part_condition = null;

    #[ORM\Column(length: 255)]
    private ?string $photos = null;


    #[ORM\Column]
    #[NotNull(message: "Quantity cannot be null.")]
    #[Positive(message: "Quantity must be a positive integer or zero.")]
    private ?int $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'items')]
    private ?Inventory $Inventory = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): static
    {
        $this->ref = $ref;

        return $this;
    }

    public function getPartCondition(): ?string
    {
        return $this->part_condition;
    }

    public function setPartCondition(string $part_condition): static
    {
        $this->part_condition = $part_condition;

        return $this;
    }

    public function getPhotos(): ?string
    {
        return $this->photos;
    }

    public function setPhotos(string $photos): static
    {
        $this->photos = $photos;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getInventory(): ?Inventory
    {
        return $this->Inventory;
    }

    public function setInventory(?Inventory $Inventory): static
    {
        $this->Inventory = $Inventory;

        return $this;
    }
}
