<?php

namespace App\Entity;

use App\Repository\CommnetRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: CommnetRepository::class)]
class Commnet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank ( message:'description doit etre non vide')]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Votre contenu doit comporter au moins {{ limit }} caractères',
        maxMessage: 'Votre contenu ne peut pas contenir plus de {{ limit }} caractères',
    )]
    private ?string $contenu = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    
    private ?\DateTimeInterface $datecommnt = null;

    #[ORM\ManyToOne(inversedBy: 'Commnet')]
    private ?Annonces $annonces = null;
    /**
     * @ORM\Column(type="boolean")
     */
    private $signale;

    #[ORM\Column]
    private ?int $Liked = 0;

    // Getter and setter for signale property
    public function getSignale(): ?bool
    {
        return $this->signale;
    }

    public function setSignale(bool $signale): self
    {
        $this->signale = $signale;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDatecommnt(): ?\DateTimeInterface
    {
        return $this->datecommnt;
    }

    public function setDatecommnt(\DateTimeInterface $datecommnt): static
    {
        $this->datecommnt = $datecommnt;

        return $this;
    }
    

    public function getAnnonces(): ?Annonces
    {
        return $this->annonces;
    }

    public function setAnnonces(?Annonces $annonces): static
    {
        $this->annonces = $annonces;

        return $this;
    }

    public function getLiked(): ?int
    {
        return $this->Liked;
    }

    public function setLiked(int $Liked): static
    {
        $this->Liked = $Liked;

        return $this;
    }
}
