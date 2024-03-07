<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @Assert\NotBlank(message="Veuillez sélectionner un événement.")
     
     */
    #[ORM\ManyToOne(inversedBy: 'user')]
    private ?Event $event = null;
    
/**
     * @Assert\NotBlank(message="Le nom ne doit pas être vide.")
     * * @Assert\Regex(
 *     pattern="/^[a-zA-Z]*$/",
 *     message="Le champ doit contenir uniquement des lettres."
 * )
     */
    #[ORM\Column(length: 255)]
    private ?string $nom = null;
 /**
     * @Assert\NotBlank(message="L'adresse ne doit pas être vide.")
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z0-9\s\-.,]*$/",
     *     message="Le champ doit contenir uniquement des lettres, chiffres, espaces, tirets et virgules."
     * )
     */
    #[ORM\Column(length: 255)]
    private ?string $address = null;
/**
     * @Assert\NotBlank(message="Le champ numéro de téléphone ne doit pas être vide.")
     * 
     * )
     */
    #[ORM\Column]
    private ?int $phone = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?User $User = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): static
    {
        $this->event = $event;

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


    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): static
    {
        $this->phone = $phone;

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
