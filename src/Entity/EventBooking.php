<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Enum\ReservationStatus;
use App\Repository\EventBookingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EventBookingRepository::class)]
#[ORM\Table(name: "event_booking")]
#[ApiResource]
class EventBooking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(["read"])]
    private ?int $id = null;

    #[ORM\Column(enumType: ReservationStatus::class)]
    private ReservationStatus $status = ReservationStatus::PENDING;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    private ?\DateTimeInterface $bookingDate = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2, nullable: true)]
    private ?string $totalAmount = null;

    #[ORM\Column(type: Types::STRING, length: 3, options: ['default' => 'EUR'])]
    private ?string $currency = 'EUR';

    #[ORM\ManyToOne(inversedBy: "bookings")]
    #[ORM\JoinColumn(name: "event_id", referencedColumnName: "id", onDelete: "SET NULL", nullable: true)]
    private ?Event $event = null;

    #[ORM\ManyToOne(inversedBy: "eventBookings")]
    #[ORM\JoinColumn(name: "booker_id", referencedColumnName: "id", onDelete: "SET NULL", nullable: true)]
    private ?User $booker = null;

    #[ORM\OneToMany(mappedBy: "eventBooking", targetEntity: EventBookingEquipment::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection $eventBookingEquipments;

    public function __construct(\DateTimeInterface $bookingDate)
    {
        $this->bookingDate = $bookingDate;
        $this->currency = 'EUR';
        $this->eventBookingEquipments = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getStatus(): ReservationStatus { return $this->status; }
    public function setStatus(ReservationStatus $status): self { $this->status = $status; return $this; }
    public function getBookingDate(): ?\DateTimeInterface { return $this->bookingDate; }
    public function setBookingDate(\DateTimeInterface $bookingDate): self { $this->bookingDate = $bookingDate; return $this; }
    public function getTotalAmount(): ?string { return $this->totalAmount; }
    public function setTotalAmount(?string $totalAmount): self { $this->totalAmount = $totalAmount; return $this; }
    public function getCurrency(): ?string { return $this->currency; }
    public function setCurrency(string $currency): self { $this->currency = $currency; return $this; }
    public function getEvent(): ?Event { return $this->event; }
    public function setEvent(?Event $event): self { $this->event = $event; return $this; }
    public function getBooker(): ?User { return $this->booker; }
    public function setBooker(?User $booker): self { $this->booker = $booker; return $this; }

    /** @return Collection<int, EventBookingEquipment> */
    public function getEventBookingEquipments(): Collection { return $this->eventBookingEquipments; }
    public function addEventBookingEquipment(EventBookingEquipment $equipment): self { if (!$this->eventBookingEquipments->contains($equipment)) { $this->eventBookingEquipments[] = $equipment; $equipment->setEventBooking($this); } return $this; }
    public function removeEventBookingEquipment(EventBookingEquipment $equipment): self { if ($this->eventBookingEquipments->removeElement($equipment)) { if ($equipment->getEventBooking() === $this) { $equipment->setEventBooking(null); }} return $this; }
}
