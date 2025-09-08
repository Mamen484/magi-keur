<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Enum\ReservationType;
use App\Repository\TimeSlotRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: TimeSlotRepository::class)]
#[ORM\Table(name: "time_slot")]
class TimeSlot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, enumType: ReservationType::class)]
    private ?ReservationType $type;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $bookableId = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private ?\DateTimeInterface $startTime = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private ?\DateTimeInterface $endTime = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    private bool $isRecurring = false;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    private bool $exception = false;

    /**
     * iCal RRULE for recurring
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $rrule = null;

    public function getId(): ?int { return $this->id; }
    public function getType(): ReservationType { return $this->type; }
    public function setType(ReservationType $type): self { $this->type = $type; return $this; }
    public function getBookableId(): ?int { return $this->bookableId; }
    public function setBookableId(?int $bookableId): self { $this->bookableId = $bookableId; return $this; }
    public function getStartTime(): ?\DateTimeInterface { return $this->startTime; }
    public function setStartTime(\DateTimeInterface $startTime): self { $this->startTime = $startTime; return $this; }
    public function getEndTime(): ?\DateTimeInterface { return $this->endTime; }
    public function setEndTime(\DateTimeInterface $endTime): self { $this->endTime = $endTime; return $this; }
    public function isRecurring(): bool { return $this->isRecurring; }
    public function setIsRecurring(bool $isRecurring): self { $this->isRecurring = $isRecurring; return $this; }
    public function getRrule(): ?string { return $this->rrule; }
    public function setRrule(?string $rrule): self { $this->rrule = $rrule; return $this; }
    public function isException(): bool { return $this->exception; }
    public function setException(bool $exception): self { $this->exception = $exception; return $this; }
}
