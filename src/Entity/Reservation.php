<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Enum\ReservationStatus;
use App\Enum\ReservationType;
use App\Repository\ReservationRepository;
use App\Service\ReservationProcessor;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
#[ORM\Table(name: "reservation")]
#[ApiResource(
    normalizationContext: ['groups' => ['reservation:read']],
    denormalizationContext: ['groups' => ['reservation:write']],
    operations: [ new Post(processor: ReservationProcessor::class) ]
)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(['reservation:read'])]
    private ?int $id = null;

    #[ORM\Column(enumType: ReservationStatus::class)]
    #[Groups(['reservation:read', 'reservation:write'])]
    private ?ReservationStatus $status = ReservationStatus::PENDING;

    #[ORM\Column(enumType: ReservationType::class)]
    #[Groups(['reservation:read', 'reservation:write'])]
    private ?ReservationType $type;

    /**
     * The ID of the bookable entity (e.g., room, car, etc.) associated with this reservation.
     */
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(['reservation:read', 'reservation:write'])]
    private ?int $bookableId;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    #[Assert\LessThan(propertyPath: 'endDate')]
    #[Groups(['reservation:read', 'reservation:write'])]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    #[Groups(['reservation:read', 'reservation:write'])]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2)]
    #[Groups(['reservation:read', 'reservation:write'])]
    private ?string $totalAmount = null;

    #[ORM\Column(type: Types::STRING, length: 3, options: ['default' => 'EUR'])]
    #[Groups(['reservation:read', 'reservation:write'])]
    private ?string $currency = 'EUR';

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    #[Groups(['reservation:read', 'reservation:write'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    #[Groups(['reservation:read', 'reservation:write'])]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2, nullable: true)]
    #[Groups(['reservation:read', 'reservation:write'])]
    private ?string $averageRating = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['reservation:read', 'reservation:write'])]
    private ?User $user = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getType(): ReservationType { return $this->type; }
    public function setType(ReservationType $type): self { $this->type = $type; return $this; }
    public function getStatus(): ReservationStatus { return $this->status; }
    public function setStatus(ReservationStatus $status): self { $this->status = $status; return $this; }

    public function getBookableId(): ?int { return $this->bookableId; } public function setBookableId(?int $bookableId): self { $this->bookableId = $bookableId; return $this; }
    public function getStartDate(): ?\DateTimeInterface { return $this->startDate; } public function setStartDate(\DateTimeInterface $startDate): self { $this->startDate = $startDate; return $this; }
    public function getEndDate(): ?\DateTimeInterface { return $this->endDate; } public function setEndDate(\DateTimeInterface $endDate): self { $this->endDate = $endDate; return $this; }
    public function getTotalAmount(): ?string { return $this->totalAmount; } public function setTotalAmount(?string $totalAmount): self { $this->totalAmount = $totalAmount; return $this; }
    public function getCurrency(): ?string { return $this->currency; } public function setCurrency(?string $currency): self { $this->currency = $currency; return $this; }

    public function getCreatedAt(): ?\DateTimeInterface { return $this->createdAt; } public function setCreatedAt(\DateTimeInterface $createdAt): self { $this->createdAt = $createdAt; return $this; }
    public function getUpdatedAt(): ?\DateTimeInterface { return $this->updatedAt; } public function setUpdatedAt(\DateTimeInterface $updatedAt): self { $this->updatedAt = $updatedAt; return $this; }
    public function getAverageRating(): ?string { return $this->averageRating; } public function setAverageRating(?string $averageRating): self { $this->averageRating = $averageRating; return $this; }
    public function getUser(): ?User { return $this->user; } public function setUser(?User $user): self { $this->user = $user; return $this; }

    // --- Méthodes métiers ---
    public function isCancelable(): bool { return $this->startDate > new \DateTimeImmutable(); }
    #[Groups(['reservation:read'])]
    public function getCanBeCancelled(): bool { return $this->isCancelable(); }
}
