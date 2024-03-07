<?php

namespace App\Entity;
use App\Repository\AnnoncesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Gedmo\Mapping\Annotation as Gedmo;  
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Symfony\Component\Validator\Constraints as Assert;

 

#[ORM\Entity(repositoryClass: AnnoncesRepository::class)]
class Annonces
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null; 
    

    #[ORM\Column(length: 255,nullable:true)]
    #[Assert\NotBlank ( message:'titre doit etre non vide')]
    #[Assert\NotNull ( message:'titre doit etre non vide')]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Votre titre doit comporter au moins {{ limit }} caractères',
        maxMessage: 'Votre titre ne peut pas contenir plus de {{ limit }} caractères',
    )]

    private ?string $titre = null;
    
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datedepub = null;

    #[ORM\Column(length: 255 ,nullable:true)]
    #[Assert\NotBlank ( message:'description doit etre non vide')]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Votre description doit comporter au moins {{ limit }} caractères',
        maxMessage: 'Votre description ne peut pas contenir plus de {{ limit }} caractères',
    )]


    

    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'annonces', targetEntity: Commnet::class)]
    private Collection $commnet;

    #[ORM\Column]
    private ?int $liked = 0;

    #[ORM\ManyToOne(inversedBy: 'annonces')]
    private ?User $User = null;
   
  
   /* private $createdAt;
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
        }*/
   
    public function __construct()
    {
        $this->commnet = new ArrayCollection();
    }

    

   


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDatedepub(): ?\DateTimeInterface
    {
        return $this->datedepub;
    }

    public function setDatedepub(\DateTimeInterface $datedepub): static
    {
        $this->datedepub = $datedepub;
    
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

    /**
     * @return Collection<int, Commentaire>
     */

    /**
     * @return Collection<int, commnet>
     */
    public function getCommnet(): Collection
    {
        return $this->commnet;
    }

    public function addCommnet(commnet $commnet): static
    {
        if (!$this->commnet->contains($commnet)) {
            $this->commnet->add($commnet);
            $commnet->setAnnonces($this);
        }

        return $this;
    }

    public function removeCommnet(commnet $commnet): static
    {
        if ($this->commnet->removeElement($commnet)) {
            // set the owning side to null (unless already changed)
            if ($commnet->getAnnonces() === $this) {
                $commnet->setAnnonces(null);
            }
        }

        return $this;
    }
    
    public function __toString()
    {
        return $this->titre;
    }

    public function getLiked(): ?int
    {
        return $this->liked;
    }

    public function setLiked(int $liked): static
    {
        $this->liked = $liked;

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
