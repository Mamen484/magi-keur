<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\ApiResource;
use App\Controller\InvoicePdfController;
use App\Enum\InvoiceStatus;
use App\Repository\InvoiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
#[ORM\Table(name: "invoice")]
#[ApiResource(
    operations: [
        new Get(),
        new Get(
            uriTemplate: '/invoices/{id}/generate-pdf',
            controller: InvoicePdfController::class,
            name: 'generate_invoice_pdf',
            extraProperties:[
                'openapiContext' => [
                    'summary' => 'Génère le PDF de la facture',
                    'description' => 'Génère un fichier PDF basé sur la facture et ses InvoiceItems'
                ],
            ],
            read: false,
            output: false
        )
    ]
)]
class Invoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING,length: 80, unique: true)]
    private ?string $invoiceNumber = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    private ?\DateTimeInterface $issueDate = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dueDate = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2)]
    private ?string $totalAmount = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2, options: ['default' => 0])]
    private ?string $totalTaxes = '0';

    #[ORM\Column(type: Types::STRING, length: 3, options: ['default' => 'EUR'])]
    private string $currency = 'EUR';

    #[ORM\Column(enumType: InvoiceStatus::class)]
    private InvoiceStatus $status = InvoiceStatus::PENDING;

    #[ORM\ManyToOne(inversedBy: "invoicesAsClient")]
    #[ORM\JoinColumn(name: "client_id", referencedColumnName: "id", onDelete: "SET NULL", nullable: true)]
    private ?User $client = null;

    #[ORM\ManyToOne(inversedBy: "invoicesAsProvider")]
    #[ORM\JoinColumn(name: "provider_id", referencedColumnName: "id", onDelete: "SET NULL", nullable: true)]
    private ?User $provider = null;

    #[ORM\ManyToOne(inversedBy: "invoices")]
    #[ORM\JoinColumn(name: "company_id", referencedColumnName: "id", onDelete: "SET NULL", nullable: true)]
    private ?Company $company = null;

    #[ORM\Column(length: 10, options: ['default' => 'fr_FR'])]
    private ?string $locale = 'fr_FR';

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, options: ['default' => 'now()'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, options: ['default' => 'now()'])]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\OneToMany(mappedBy: "invoice", targetEntity: InvoiceItem::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection $items;

    public function __construct(string $invoiceNumber, \DateTimeInterface $issueDate, InvoiceStatus $status)
    {
        $this->invoiceNumber = $invoiceNumber;
        $this->issueDate = $issueDate;
        $this->status = $status;
        $this->currency = 'EUR';
        $this->locale = 'fr_FR';
        $this->totalTaxes = '0';
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getInvoiceNumber(): ?string { return $this->invoiceNumber; }
    public function setInvoiceNumber(string $invoiceNumber): self { $this->invoiceNumber = $invoiceNumber; return $this; }
    public function getIssueDate(): ?\DateTimeInterface { return $this->issueDate; }
    public function setIssueDate(\DateTimeInterface $issueDate): self { $this->issueDate = $issueDate; return $this; }
    public function getDueDate(): ?\DateTimeInterface { return $this->dueDate; }
    public function setDueDate(?\DateTimeInterface $dueDate): self { $this->dueDate = $dueDate; return $this; }
    public function getTotalAmount(): ?string { return $this->totalAmount; }
    public function setTotalAmount(string $totalAmount): self { $this->totalAmount = $totalAmount; return $this; }
    public function getTotalTaxes(): ?string { return $this->totalTaxes; }
    public function setTotalTaxes(string $totalTaxes): self { $this->totalTaxes = $totalTaxes; return $this; }
    public function getCurrency(): ?string { return $this->currency; }
    public function setCurrency(string $currency): self { $this->currency = $currency; return $this; }
    public function getStatus(): InvoiceStatus { return $this->status; }
    public function setStatus(InvoiceStatus $status): self { $this->status = $status; return $this; }
    public function getClient(): ?User { return $this->client; }
    public function setClient(?User $client): self { $this->client = $client; return $this; }
    public function getProvider(): ?User { return $this->provider; }
    public function setProvider(?User $provider): self { $this->provider = $provider; return $this; }
    public function getCompany(): ?Company { return $this->company; }
    public function setCompany(?Company $company): self { $this->company = $company; return $this; }
    public function getLocale(): ?string { return $this->locale; }
    public function setLocale(string $locale): self { $this->locale = $locale; return $this; }
    public function getCreatedAt(): ?\DateTimeInterface { return $this->createdAt; }
    public function setCreatedAt(\DateTimeInterface $createdAt): self { $this->createdAt = $createdAt; return $this; }
    public function getUpdatedAt(): ?\DateTimeInterface { return $this->updatedAt; }
    public function setUpdatedAt(\DateTimeInterface $updatedAt): self { $this->updatedAt = $updatedAt; return $this; }

    /** @return Collection<int, InvoiceItem> */
    public function getItems(): Collection { return $this->items; }
    public function addItem(InvoiceItem $item): self { if (!$this->items->contains($item)) { $this->items->add($item); $item->setInvoice($this); } return $this; }
    public function removeItem(InvoiceItem $item): self { if ($this->items->removeElement($item)) { if ($item->getInvoice() === $this) { $item->setInvoice(null); } } return $this; }


    public function calculateTotalAmount(): float
    {
        $this->totalAmount = array_sum(array_map(fn(InvoiceItem $item) => $item->getTotal(), $this->items->toArray()));
        return $this->totalAmount;
    }
}
