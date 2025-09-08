<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Enum\ReservationType;
use App\Repository\AvailabilityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AvailabilityRepository::class)]
#[ORM\Table(name: "availability")]
#[ApiResource(
    normalizationContext: ['groups' => ['availability:read']],
    denormalizationContext: ['groups' => ['availability:write']]
)]
class Availability
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(['availability:read'])]
    private ?int $id = null;

    #[ORM\Column(enumType: ReservationType::class)]
    #[Groups(['availability:read', 'availability:write'])]
    private ?ReservationType $type;

    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(['availability:read', 'availability:write'])]
    private ?int $bookableId;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    #[Groups(['availability:read', 'availability:write'])]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    #[Groups(['availability:read', 'availability:write'])]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => true])]
    #[Groups(['availability:read', 'availability:write'])]
    private bool $isAvailable = true;

    public function getId(): ?int { return $this->id; }
    public function getType(): ReservationType { return $this->type; }
    public function setType(ReservationType $type): self { $this->type = $type; return $this; }
    public function getBookableId(): ?int { return $this->bookableId; }
    public function setBookableId(?int $bookableId): self { $this->bookableId = $bookableId; return $this; }
    public function getStartDate(): ?\DateTimeInterface { return $this->startDate; }
    public function setStartDate(\DateTimeInterface $startDate): self { $this->startDate = $startDate; return $this; }
    public function getEndDate(): ?\DateTimeInterface { return $this->endDate; }
    public function setEndDate(\DateTimeInterface $endDate): self { $this->endDate = $endDate; return $this; }
    public function isAvailable(): bool { return $this->isAvailable; }
    public function setIsAvailable(bool $isAvailable): self { $this->isAvailable = $isAvailable; return $this; }
}
