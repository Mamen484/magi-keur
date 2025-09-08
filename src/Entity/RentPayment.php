<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Enum\PaymentMethod;
use App\Enum\PaymentStatus;
use App\Repository\RentPaymentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RentPaymentRepository::class)]
#[ORM\Table(name: "rent_payment")]
#[ApiResource(
    normalizationContext: ['groups' => ['rent_payment:read']],
    denormalizationContext: ['groups' => ['rent_payment:write']]
)]
class RentPayment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(['rent_payment:read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2)]
    #[Groups(['rent_payment:read', 'rent_payment:write'])]
    private ?string $amount = null;

    #[ORM\Column(type: Types::STRING, length: 3, options: ['default' => 'EUR'])]
    private ?string $currency = 'EUR';

    #[ORM\Column(enumType: PaymentMethod::class)]
    #[Groups(['rent_payment:read', 'rent_payment:write'])]
    private PaymentMethod $method;

    #[ORM\Column(enumType: PaymentStatus::class)]
    #[Groups(['rent_payment:read', 'rent_payment:write'])]
    private PaymentStatus $status = PaymentStatus::PENDING;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['rent_payment:read', 'rent_payment:write'])]
    private ?string $receiptPath = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    #[Groups(['rent_payment:read', 'rent_payment:write'])]
    private ?\DateTimeImmutable $dueDate;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable:true)]
    #[Groups(['rent_payment:read', 'rent_payment:write'])]
    private ?\DateTimeImmutable $paidDate = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, options: ["default" => "now()"])]
    #[Groups(['rent_payment:read'])]
    private ?\DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(inversedBy: "rentPayments")]
    #[ORM\JoinColumn(name: "lease_id", referencedColumnName: "id", onDelete: "SET NULL", nullable: true)]
    private ?Lease $lease = null;

    #[ORM\ManyToOne(inversedBy: "rentPayments")]
    #[ORM\JoinColumn(name: "tenant_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private ?User $tenant = null;

    public function __construct(string $amount, PaymentMethod $method, PaymentStatus $status, \DateTimeInterface $dueDate, User $tenant)
    {
        $this->amount = $amount;
        $this->currency = 'EUR';
        $this->method = $method;
        $this->status = $status;
        $this->dueDate = $dueDate;
        $this->tenant = $tenant;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getAmount(): ?string { return $this->amount; }
    public function setAmount(string $amount): self { $this->amount = $amount; return $this; }
    public function getCurrency(): ?string { return $this->currency; }
    public function setCurrency(string $currency): self { $this->currency = $currency; return $this; }
    public function getMethod(): PaymentMethod { return $this->method; }
    public function setMethod(PaymentMethod $method): self { $this->method = $method; return $this; }
    public function getStatus(): PaymentStatus { return $this->status; }
    public function setStatus(PaymentStatus $status): static { $this->status = $status; return $this; }
    public function getReceiptPath(): ?string { return $this->receiptPath; }
    public function setReceiptPath(?string $receiptPath): self { $this->receiptPath = $receiptPath; return $this; }
    public function getDueDate(): ?\DateTimeInterface { return $this->dueDate; }
    public function setDueDate(\DateTimeInterface $dueDate): self { $this->dueDate = $dueDate; return $this; }
    public function getPaidDate(): ?\DateTimeInterface { return $this->paidDate; }
    public function setPaidDate(?\DateTimeInterface $paidDate): self { $this->paidDate = $paidDate; return $this; }
    public function getCreatedAt(): ?\DateTimeInterface { return $this->createdAt; }
    public function setCreatedAt(\DateTimeInterface $createdAt): self { $this->createdAt = $createdAt; return $this; }
    public function getLease(): ?Lease { return $this->lease; }
    public function setLease(?Lease $lease): self { $this->lease = $lease; return $this; }
    public function getTenant(): ?User { return $this->tenant; }
    public function setTenant(User $tenant): self { $this->tenant = $tenant; return $this; }
}
