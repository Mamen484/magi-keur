<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
#[ORM\Table(name: "company")]
#[ApiResource(
    normalizationContext: ['groups' => ['company:read']],
    denormalizationContext: ['groups' => ['company:write']]
)]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(['company:read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length:200)]
    #[Groups(['company:read', 'company:write'])]
    private ?string $name;

    #[ORM\Column(type: Types::TEXT, nullable:true)]
    #[Groups(['company:read', 'company:write'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::STRING, length:100, nullable:true)]
    #[Groups(['company:read', 'company:write'])]
    private ?string $taxId = null;

    #[ORM\Column(type: Types::STRING, options: ["default" => "fr_FR"])]
    #[Groups(['company:read', 'company:write'])]
    private ?string $locale = 'fr_FR';

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    #[Groups(['company:read'])]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    #[Groups(['company:read', 'company:write'])]
    private \DateTimeImmutable $updatedAt;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, nullable: true)]
    #[Groups(['company:read', 'company:write'])]
    private ?\DateTimeImmutable $deletedAt = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    #[Groups(['company:read'])]
    private ?int $createdBy = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    #[Groups(['company:read', 'company:write'])]
    private ?int $updatedBy = null;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Activity::class)]
    private Collection $activities;

    #[ORM\OneToMany(mappedBy: "company", targetEntity: Event::class)]
    private Collection $events;

    #[ORM\OneToMany(mappedBy: "company", targetEntity: Invoice::class)]
    private Collection $invoices;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->activities = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->invoices = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(?string $name): self { $this->name = $name; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): self { $this->description = $description; return $this; }
    public function getTaxId(): ?string { return $this->taxId; }
    public function setTaxId(?string $taxId): self { $this->taxId = $taxId; return $this; }
    public function getLocale(): ?string { return $this->locale; }
    public function setLocale(?string $locale): self { $this->locale = $locale; return $this; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): self { $this->createdAt = $createdAt; return $this; }
    public function getUpdatedAt(): \DateTimeImmutable { return $this->updatedAt; }
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self { $this->updatedAt = $updatedAt; return $this; }
    public function getDeletedAt(): ?\DateTimeImmutable { return $this->deletedAt; }
    public function setDeletedAt(?\DateTimeImmutable $deletedAt): self { $this->deletedAt = $deletedAt; return $this; }
    public function getCreatedBy(): ?int { return $this->createdBy; }
    public function setCreatedBy(?int $createdBy): self { $this->createdBy = $createdBy; return $this; }
    public function getUpdatedBy(): ?int { return $this->updatedBy; }
    public function setUpdatedBy(?int $updatedBy): self { $this->updatedBy = $updatedBy; return $this; }

    /** @return Collection<int, Activity> */
    public function getActivities(): Collection { return $this->activities; }
    public function addActivity(Activity $activity): self { if (!$this->activities->contains($activity)) { $this->activities->add($activity); $activity->setCompany($this); } return $this; }
    public function removeActivity(Activity $activity): self { if ($this->activities->removeElement($activity)) { if ($activity->getCompany() === $this) { $activity->setCompany(null); }} return $this; }

    /** @return Collection<int, Event> */
    public function getEvents(): Collection { return $this->events; }
    public function addEvent(Event $event): self { if (!$this->events->contains($event)) { $this->events[] = $event; $event->setCompany($this); } return $this; }
    public function removeEvent(Event $event): self { if ($this->events->removeElement($event)) { if ($event->getCompany() === $this) { $event->setCompany(null); }} return $this; }

    /** @return Collection<int, Invoice> */
    public function getInvoices(): Collection { return $this->invoices; }
    public function addInvoice(Invoice $invoice): self { if (!$this->invoices->contains($invoice)) { $this->invoices[] = $invoice; $invoice->setCompany($this); } return $this; }
    public function removeInvoice(Invoice $invoice): self { if ($this->invoices->removeElement($invoice)) { if ($invoice->getCompany() === $this) { $invoice->setCompany(null); }} return $this; }
}
