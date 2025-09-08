<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\EventBookingEquipmentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventBookingEquipmentRepository::class)]
#[ORM\Table(name: "event_booking_equipment")]
#[ApiResource]
class EventBookingEquipment
{
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: "eventBookingEquipments")]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?EventBooking $eventBooking = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: "eventBookingEquipments")]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Equipment $equipment = null;

    public function getEventBooking(): ?EventBooking { return $this->eventBooking; }
    public function setEventBooking(?EventBooking $eventBooking): self { $this->eventBooking = $eventBooking; return $this; }
    public function getEquipment(): ?Equipment { return $this->equipment; }
    public function setEquipment(?Equipment $equipment): self { $this->equipment = $equipment; return $this; }
}
