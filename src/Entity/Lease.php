<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\LeaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LeaseRepository::class)]
#[ORM\Table(name: "lease")]
#[ApiResource]
class Lease
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2, nullable: true)]
    private ?string $rentAmount = null;

    #[ORM\Column(type: Types::STRING, length: 3, options: ['default' => 'EUR'])]
    private ?string $currency = 'EUR';

    #[ORM\ManyToOne(inversedBy: "leases")]
    #[ORM\JoinColumn(name: "property_id", referencedColumnName: "id", onDelete: "SET NULL", nullable: true)]
    private ?Property $property = null;

    #[ORM\ManyToOne(inversedBy: "leases")]
    #[ORM\JoinColumn(name: "tenant_id", referencedColumnName: "id", onDelete: "SET NULL", nullable: true)]
    private ?User $tenant = null;

    #[ORM\OneToMany(mappedBy: "lease", targetEntity: RentPayment::class, cascade: ["persist", "remove"])]
    private Collection $rentPayments;

    public function __construct(\DateTimeInterface $startDate)
    {
        $this->startDate = $startDate;
        $this->currency = 'EUR';
        $this->rentPayments = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getStartDate(): ?\DateTimeInterface { return $this->startDate; }
    public function setStartDate(\DateTimeInterface $startDate): self { $this->startDate = $startDate; return $this; }
    public function getEndDate(): ?\DateTimeInterface { return $this->endDate; }
    public function setEndDate(?\DateTimeInterface $endDate): self { $this->endDate = $endDate; return $this; }
    public function getRentAmount(): ?string { return $this->rentAmount; }
    public function setRentAmount(?string $rentAmount): self { $this->rentAmount = $rentAmount; return $this; }
    public function getCurrency(): ?string { return $this->currency; }
    public function setCurrency(string $currency): self { $this->currency = $currency; return $this; }
    public function getProperty(): ?Property { return $this->property; }
    public function setProperty(?Property $property): self { $this->property = $property; return $this; }
    public function getTenant(): ?User { return $this->tenant; }
    public function setTenant(?User $tenant): self { $this->tenant = $tenant; return $this; }

    /** @return Collection<int, RentPayment> */
    public function getRentPayments(): Collection { return $this->rentPayments; }
    public function addRentPayment(RentPayment $payment): self { if (!$this->rentPayments->contains($payment)) { $this->rentPayments[] = $payment; $payment->setLease($this); } return $this; }
    public function removeRentPayment(RentPayment $payment): self { if ($this->rentPayments->removeElement($payment)) { if ($payment->getLease() === $this) { $payment->setLease(null); }} return $this; }
}
