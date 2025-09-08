<?php

namespace App\Entity;

use App\Enum\PaymentStatus;
use App\Repository\PayoutRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PayoutRepository::class)]
#[ORM\Table(name: "payout")]
class Payout
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Company::class)]
    #[ORM\JoinColumn(onDelete: "SET NULL")]
    private ?Company $company = null;

    #[ORM\ManyToOne(targetEntity: Supplier::class)]
    #[ORM\JoinColumn(onDelete: "SET NULL")]
    private ?Supplier $supplier = null;

    #[ORM\ManyToOne(targetEntity: PaymentProvider::class)]
    #[ORM\JoinColumn(onDelete: "SET NULL")]
    private ?PaymentProvider $provider = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2)]
    private ?string $amount = null;

    #[ORM\Column(type: Types::STRING, length: 3, options: ['default' => 'EUR'])]
    private ?string $currency = "EUR";

    #[ORM\Column(enumType: PaymentStatus::class)]
    private PaymentStatus $status;

    #[ORM\Column(type: Types::STRING, length: 120, nullable: true)]
    private ?string $externalTransferId = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $paidAt = null;

    public function getId(): ?int { return $this->id; }
    public function getCompany(): ?Company { return $this->company; }
    public function setCompany(?Company $company): self { $this->company = $company; return $this; }
    public function getSupplier(): ?Supplier { return $this->supplier; }
    public function setSupplier(?Supplier $supplier): self { $this->supplier = $supplier; return $this; }
    public function getProvider(): ?PaymentProvider { return $this->provider; }
    public function setProvider(?PaymentProvider $provider): self { $this->provider = $provider; return $this; }
    public function getAmount(): ?string { return $this->amount; }
    public function setAmount(?string $amount): self { $this->amount = $amount; return $this; }
    public function getCurrency(): ?string { return $this->currency; }
    public function setCurrency(?string $currency): self { $this->currency = $currency; return $this; }
    public function getStatus(): PaymentStatus { return $this->status; }
    public function setStatus(PaymentStatus $status): self { $this->status = $status; return $this; }
    public function getExternalTransferId(): ?string { return $this->externalTransferId; }
    public function setExternalTransferId(?string $externalTransferId): self { $this->externalTransferId = $externalTransferId; return $this; }
    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): self { $this->createdAt = $createdAt; return $this; }
    public function getPaidAt(): ?\DateTimeImmutable { return $this->paidAt; }
    public function setPaidAt(?\DateTimeImmutable $paidAt): self { $this->paidAt = $paidAt; return $this; }
}
