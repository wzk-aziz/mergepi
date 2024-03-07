<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EchangeRepository;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: EchangeRepository::class)]
class Echange
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

  
   
    #[ORM\Column(length: 255, options: ["default" => "non validé"])]
    
    private ?string $etat = null;

    #[ORM\Column(length: 255)]
    #[Length(min: 3)]
    #[NotBlank]
    private ?string $offre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'echanges')]
    private ?User $User = null;


    public function getId(): ?int
    {
        return $this->id;
    }

 

    /* public function setInventoryId(int $inventory_id): static
    {
        $this->inventory_id = $inventory_id;

        return $this;
    }*/

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getOffre(): ?string
    {
        return $this->offre;
    }

    public function setOffre(string $offre): static
    {
        $this->offre = $offre;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
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
