<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\InvoiceItemRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: InvoiceItemRepository::class)]
#[ORM\Table(name: "invoice_item")]
#[ApiResource(
    normalizationContext: ['groups' => ['invoicetem:read']],
    denormalizationContext: ['groups' => ['invoicetem:write']]
)]
class InvoiceItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['invoicetem:read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['invoicetem:read', 'invoicetem:write'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2)]
    #[Groups(['invoicetem:read', 'invoicetem:write'])]
    private ?string $quantity = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2)]
    #[Groups(['invoicetem:read', 'invoicetem:write'])]
    private ?string $unitPrice = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2)]
    #[Groups(['invoicetem:read', 'invoicetem:write'])]
    private ?string $total = null;

    #[ORM\Column(type: Types::STRING, length: 3, options: ['default' => 'EUR'])]
    #[Groups(['invoicetem:read', 'invoicetem:write'])]
    private ?string $currency = 'EUR';

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: "tax_rate_id", referencedColumnName: "id", onDelete: "SET NULL", nullable: true)]
    private ?TaxRate $taxRate = null;

    #[ORM\ManyToOne(inversedBy: "items")]
    #[ORM\JoinColumn(name: "invoice_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private ?Invoice $invoice = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, options: ["default" => "now()"])]
    #[Groups(['invoicetem:read', 'invoicetem:write'])]
    private \DateTimeImmutable $createdAt;

    public function __construct(string $description, string $quantity, string $unitPrice, string $total, Invoice $invoice)
    {
        $this->description = $description;
        $this->quantity = $quantity;
        $this->unitPrice = $unitPrice;
        $this->total = $total;
        $this->invoice = $invoice;
        $this->currency = 'EUR';
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): self { $this->description = $description; return $this; }
    public function getQuantity(): ?string { return $this->quantity; }
    public function setQuantity(string $quantity): self { $this->quantity = $quantity; return $this; }
    public function getUnitPrice(): ?string { return $this->unitPrice; }
    public function setUnitPrice(string $unitPrice): self { $this->unitPrice = $unitPrice; return $this; }
    public function getTotal(): ?string { return $this->total; }
    public function setTotal(string $total): self { $this->total = $total; return $this; }
    public function getCurrency(): ?string { return $this->currency; }
    public function setCurrency(string $currency): self { $this->currency = $currency; return $this; }
    public function getTaxRate(): ?TaxRate { return $this->taxRate; }
    public function setTaxRate(?TaxRate $taxRate): self { $this->taxRate = $taxRate; return $this; }
    public function getCreatedAt(): ?\DateTimeInterface { return $this->createdAt; }
    public function setCreatedAt(\DateTimeInterface $createdAt): self { $this->createdAt = $createdAt; return $this; }
    public function getInvoice(): ?Invoice { return $this->invoice; }
    public function setInvoice(?Invoice $invoice): self { $this->invoice = $invoice; return $this; }
}
