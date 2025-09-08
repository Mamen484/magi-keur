<?php

namespace App\Entity;

use App\Repository\RefundRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RefundRepository::class)]
#[ORM\Table(name: "refund")]
class Refund
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Payment::class, inversedBy: "refunds")]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Payment $payment = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2)]
    private ?string $amount = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $reason = null;

    #[ORM\Column(type: Types::STRING, length: 50)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $processedAt = null;

    public function getId(): ?int { return $this->id; }
    public function getPayment(): ?Payment { return $this->payment; }
    public function setPayment(?Payment $payment): self { $this->payment = $payment; return $this; }
    public function getAmount(): ?string { return $this->amount; }
    public function setAmount(?string $amount): self { $this->amount = $amount; return $this; }
    public function getReason(): ?string { return $this->reason; }
    public function setReason(?string $reason): self { $this->reason = $reason; return $this; }
    public function getStatus(): ?string { return $this->status; }
    public function setStatus(?string $status): self { $this->status = $status; return $this; }
    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): self { $this->createdAt = $createdAt; return $this; }
    public function getProcessedAt(): ?\DateTimeImmutable { return $this->processedAt; }
    public function setProcessedAt(?\DateTimeImmutable $processedAt): self { $this->processedAt = $processedAt; return $this; }
}
