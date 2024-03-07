<?php

namespace App\Entity;

use App\Repository\EventRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;



#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
/**
 * @Assert\NotBlank(message="Le champ ne doit pas être vide.")
 * @Assert\Regex(
 *     pattern="/^[a-zA-Z0-9]*$/",
 *     message="Le champ doit contenir uniquement des lettres et des chiffres."
 * )
 */
    #[ORM\Column(length: 255)]
    private ?string $eventName = null;
  /**
     * @Assert\NotBlank(message="Le champ ne doit pas être vide.")
     * * @Assert\PositiveOrZero(message="Le champ ne doit pas être négatif.")
     * * @Assert\Regex(
 *     pattern="/^[0-9]*$/",
 *     message="Le champ doit contenir uniquement des chiffres."
 * )
     */
    #[ORM\Column]
    private ?int $capacity = null;






 /**
     * @ORM\Column(type="date")
     * @Assert\NotNull(message="La date de l'événement est requise.")
     * @Assert\Type("\DateTimeInterface")
     * @Assert\GreaterThanOrEqual("today", message="La date de l'événement doit être postérieure  à aujourd'hui.")
**/
#[ORM\Column]
private ?DateTime $startDate;



/**
 * @Assert\GreaterThanOrEqual(propertyPath="startDate", message="The end date must be greater than  the start date.")
 * @ORM\Column(type="date", nullable=true)
*/
#[ORM\Column]
private ?DateTime $endDate;




/**
     * @Assert\NotBlank(message="Le champ description ne doit pas être vide.")
     * * @Assert\Regex(
 *     pattern="/^[a-zA-Z]*$/",
 *     message="Le champ doit contenir uniquement des lettres."
 * )
     */
    #[ORM\Column(length: 255)]
    private ?string $place = null;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Reservation::class)]
    private Collection $user;

    #[ORM\Column(length: 1023)]
    private ?string $description = null;
 /**
     * @Assert\NotBlank(message="Veuillez sélectionner une image.")
     * @Assert\File(
     *     maxSize="5M",
     *     mimeTypes={"image/jpeg", "image/png", "image/gif"},
     *     maxSizeMessage="La taille maximale autorisée pour l'image est 5MB.",
     *     mimeTypesMessage="Veuillez télécharger une image au format JPG, PNG ou GIF."
     * )
     */
    #[ORM\Column(length: 255)]
    private ?string $image = null;

    public function __construct()
    {
        $this->user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEventName(): ?string
    {
        return $this->eventName;
    }

    public function setEventName(string $eventName): static
    {
        $this->eventName = $eventName;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): static
    {
        $this->capacity = $capacity;

        return $this;
    }
    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }
    
    public function setStartDate(DateTime $startDate): static
    {
        $this->startDate = $startDate;
    
        return $this;
    }

    public function getEndDate(): ?DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(DateTime $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): static
    {
        $this->place = $place;

        return $this;
    }
    public function __toString(): string
    {
        return $this->getEventName();
        
    }
   

    /**
     * @return Collection<int, Reservation>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(Reservation $user): static
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
            $user->setEvent($this);
        }

        return $this;
    }

    public function removeUser(Reservation $user): static
    {
        if ($this->user->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getEvent() === $this) {
                $user->setEvent(null);
            }
        }

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

     /**
     * @Assert\Callback
     */
    public function validateEndDate(ExecutionContextInterface $context): void
    {
        $today = new \DateTime('today');

        if ($this->endDate !== null && $this->endDate < $today) {
            $context->buildViolation('The end date cannot be earlier than today.')
                ->atPath('endDate')
                ->addViolation();
        }
    }
    
}
