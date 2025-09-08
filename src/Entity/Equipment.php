<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\EquipmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EquipmentRepository::class)]
#[ORM\Table(name: "equipment")]
#[ApiResource]
class Equipment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length:150)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable:true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2, nullable: true)]
    private ?string $rentalPricePerDay = null;

    #[ORM\Column(type: Types::STRING, length: 3, options: ['default' => 'EUR'])]
    private ?string $currency = 'EUR';

    #[ORM\OneToMany(mappedBy: "equipment", targetEntity: EventBookingEquipment::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection $eventBookingEquipments;

    public function __construct()
    {
        $this->eventBookingEquipments = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): self { $this->description = $description; return $this; }
    public function getRentalPricePerDay(): ?string { return $this->rentalPricePerDay; }
    public function setRentalPricePerDay(?string $rentalPricePerDay): self { $this->rentalPricePerDay = $rentalPricePerDay; return $this; }
    public function getCurrency(): ?string { return $this->currency; }
    public function setCurrency(string $currency): self { $this->currency = $currency; return $this; }

    /** @return Collection<int, EventBookingEquipment> */
    public function getEventBookingEquipments(): Collection { return $this->eventBookingEquipments; }
    public function addEventBookingEquipment(EventBookingEquipment $bookingEquipment): self { if (!$this->eventBookingEquipments->contains($bookingEquipment)) { $this->eventBookingEquipments[] = $bookingEquipment; $bookingEquipment->setEquipment($this); } return $this; }
    public function removeEventBookingEquipment(EventBookingEquipment $bookingEquipment): self { if ($this->eventBookingEquipments->removeElement($bookingEquipment)) { if ($bookingEquipment->getEquipment() === $this) { $bookingEquipment->setEquipment(null); }} return $this; }
}
