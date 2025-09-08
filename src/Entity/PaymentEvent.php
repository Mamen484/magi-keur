<?php

namespace App\Entity;

use App\Enum\EventType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "payment_event")]
class PaymentEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Payment::class, inversedBy: "events")]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Payment $payment = null;

    #[ORM\Column(enumType: EventType::class)]
    private ?EventType $eventType;

    /**
     * @var string[]
     */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $payload = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int { return $this->id; }
    public function getPayment(): ?Payment { return $this->payment; }
    public function setPayment(?Payment $payment): self { $this->payment = $payment; return $this; }
    public function getEventType(): ?EventType { return $this->eventType; }
    public function setEventType(?EventType $eventType): self { $this->eventType = $eventType; return $this; }
    public function getPayload(): ?array { return $this->payload; }
    public function setPayload(?array $payload): self { $this->payload = $payload; return $this; }
    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): self { $this->createdAt = $createdAt; return $this; }
}
