<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Enum\EventStatus;
use App\Enum\EventType;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ORM\Table(name: "event")]
#[ApiResource(
    normalizationContext: ['groups' => ['event:read']],
    denormalizationContext: ['groups' => ['event:write']]
)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(['event:read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING,length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(enumType: EventType::class)]
    private EventType $type;

    #[ORM\Column(enumType: EventStatus::class)]
    private EventStatus $status;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    private ?\DateTimeInterface $startDatetime = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endDatetime = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2, nullable: true)]
    private ?string $price = null;

    #[ORM\Column(type: Types::STRING, length: 3, options: ['default' => 'EUR'])]
    private ?string $currency = 'EUR';

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $maxGuests = null;

    #[ORM\ManyToOne(inversedBy: "organizedEvents")]
    #[ORM\JoinColumn(name: "organizer_id", referencedColumnName: "id", onDelete: "SET NULL", nullable: true)]
    private ?User $organizer = null;

    #[ORM\ManyToOne(inversedBy: "events")]
    #[ORM\JoinColumn(name: "venue_id", referencedColumnName: "id", onDelete: "SET NULL", nullable: true)]
    private ?Venue $venue = null;

    #[ORM\ManyToOne(inversedBy: "events")]
    #[ORM\JoinColumn(name: "company_id", referencedColumnName: "id", onDelete: "SET NULL", nullable: true)]
    private ?Company $company = null;

    #[ORM\OneToMany(mappedBy: "event", targetEntity: EventBooking::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection $bookings;

    #[ORM\OneToMany(mappedBy: "event", targetEntity: Guest::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection $guests;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, options: ['default' => 'now()'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, options: ['default' => 'now()'])]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->bookings = new ArrayCollection();
        $this->guests = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getType(): EventType { return $this->type; }
    public function setType(EventType $type): self { $this->type = $type; return $this; }
    public function getStatus(): EventStatus { return $this->status; }
    public function setStatus(EventStatus $status): self { $this->status = $status; return $this; }
    public function getTitle(): ?string { return $this->title; }
    public function setTitle(string $title): self { $this->title = $title; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): self { $this->description = $description; return $this; }
    public function getStartDatetime(): ?\DateTimeInterface { return $this->startDatetime; }
    public function setStartDatetime(\DateTimeInterface $startDatetime): self { $this->startDatetime = $startDatetime; return $this; }
    public function getEndDatetime(): ?\DateTimeInterface { return $this->endDatetime; }
    public function setEndDatetime(?\DateTimeInterface $endDatetime): self { $this->endDatetime = $endDatetime; return $this; }
    public function getPrice(): ?string { return $this->price; }
    public function setPrice(?string $price): self { $this->price = $price; return $this; }
    public function getCurrency(): ?string { return $this->currency; }
    public function setCurrency(string $currency): self { $this->currency = $currency; return $this; }
    public function getMaxGuests(): ?int { return $this->maxGuests; }
    public function setMaxGuests(?int $maxGuests): self { $this->maxGuests = $maxGuests; return $this; }
    public function getOrganizer(): ?User { return $this->organizer; }
    public function setOrganizer(?User $organizer): self { $this->organizer = $organizer; return $this; }
    public function getVenue(): ?Venue { return $this->venue; }
    public function setVenue(?Venue $venue): self { $this->venue = $venue; return $this; }
    public function getCompany(): ?Company { return $this->company; }
    public function setCompany(?Company $company): self { $this->company = $company; return $this; }
    public function getCreatedAt(): ?\DateTimeInterface { return $this->createdAt; }
    public function setCreatedAt(\DateTimeInterface $createdAt): self { $this->createdAt = $createdAt; return $this; }
    public function getUpdatedAt(): ?\DateTimeInterface { return $this->updatedAt; }
    public function setUpdatedAt(\DateTimeInterface $updatedAt): self { $this->updatedAt = $updatedAt; return $this; }

    /** @return Collection<int, EventBooking> */
    public function getBookings(): Collection { return $this->bookings; }
    public function addBooking(EventBooking $booking): self { if (!$this->bookings->contains($booking)) { $this->bookings[] = $booking; $booking->setEvent($this); } return $this; }
    public function removeBooking(EventBooking $booking): self { if ($this->bookings->removeElement($booking)) { if ($booking->getEvent() === $this) { $booking->setEvent(null); }} return $this; }

    /** @return Collection<int, Guest> */
    public function getGuests(): Collection { return $this->guests; }
    public function addGuest(Guest $guest): self { if (!$this->guests->contains($guest)) { $this->guests[] = $guest; $guest->setEvent($this); } return $this; }
    public function removeGuest(Guest $guest): self { if ($this->guests->removeElement($guest)) { if ($guest->getEvent() === $this) { $guest->setEvent(null); }} return $this; }
}
