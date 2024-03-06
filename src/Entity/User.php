<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $Id = null;

    #[ORM\Column]
    #[ORM\GeneratedValue]
    private ?int $inventory_id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\ManyToOne(inversedBy: 'user')]
    #[ORM\GeneratedValue]
    private ?Echange $echange = null;

    public function getId(): ?int
    {
        return $this->Id;
    }

    public function getInventoryId(): ?int
    {
        return $this->inventory_id;
    }

    public function setInventoryId(int $inventory_id): static
    {
        $this->inventory_id = $inventory_id;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getEchange(): ?Echange
    {
        return $this->echange;
    }

    public function setEchange(?Echange $echange): static
    {
        $this->echange = $echange;

        return $this;
    }
}
