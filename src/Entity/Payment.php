<?php

namespace App\Entity;

use App\Enum\PaymentMethod;
use App\Enum\PaymentStatus;
use App\Repository\PaymentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
#[ORM\Table(name: "payment")]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2)]
    private string $amount;

    #[ORM\Column(type: Types::STRING, length: 3, options: ["default" => "EUR"])]
    private string $currency = 'EUR';

    #[ORM\Column(enumType: PaymentMethod::class)]
    private PaymentMethod $method;

    #[ORM\Column(enumType: PaymentStatus::class)]
    private PaymentStatus $status;

    #[ORM\Column(type: Types::STRING, length: 100, nullable: true)]
    private ?string $transactionId = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $paidAt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: "invoice_id", referencedColumnName: "id", onDelete: "SET NULL", nullable: true)]
    private ?Invoice $invoice = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: "payer_id", referencedColumnName: "id", onDelete: "SET NULL", nullable: true)]
    private ?User $payer = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: "reservation_id", referencedColumnName: "id", onDelete: "SET NULL", nullable: true)]
    private ?Reservation $reservation = null;

    #[ORM\OneToOne]
    #[ORM\JoinColumn(name: "event_booking_id", referencedColumnName: "id", onDelete: "SET NULL", unique: true, nullable: true)]
    private ?EventBooking $eventBooking = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: "provider_id", referencedColumnName: "id", onDelete: "SET NULL", nullable: true)]
    private ?PaymentProvider $provider = null;

    #[ORM\OneToMany(mappedBy: "payment", targetEntity: PaymentEvent::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection $events;

    #[ORM\OneToMany(mappedBy: "payment", targetEntity: Refund::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection $refunds;

    public function __construct(string $amount, PaymentMethod $method, PaymentStatus $status, string $currency = 'EUR')
    {
        $this->amount = $amount;
        $this->method = $method;
        $this->status = $status;
        $this->currency = $currency;
        $this->events = new ArrayCollection();
        $this->refunds = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getAmount(): string { return $this->amount; }
    public function setAmount(string $amount): self { $this->amount = $amount; return $this; }
    public function getCurrency(): string { return $this->currency; }
    public function setCurrency(string $currency): self { $this->currency = $currency; return $this; }
    public function getMethod(): PaymentMethod { return $this->method; }
    public function setMethod(PaymentMethod $method): self { $this->method = $method; return $this; }
    public function getStatus(): PaymentStatus { return $this->status; }
    public function setStatus(PaymentStatus $status): self { $this->status = $status; return $this; }
    public function getTransactionId(): ?string { return $this->transactionId; }
    public function setTransactionId(?string $transactionId): self { $this->transactionId = $transactionId; return $this; }
    public function getPaidAt(): ?\DateTimeImmutable { return $this->paidAt; }
    public function setPaidAt(?\DateTimeImmutable $paidAt): self { $this->paidAt = $paidAt; return $this; }
    public function getInvoice(): ?Invoice { return $this->invoice; }
    public function setInvoice(?Invoice $invoice): self { $this->invoice = $invoice; return $this; }
    public function getPayer(): ?User { return $this->payer; }
    public function setPayer(?User $payer): self { $this->payer = $payer; return $this; }
    public function getReservation(): ?Reservation { return $this->reservation; }
    public function setReservation(?Reservation $reservation): self { $this->reservation = $reservation; return $this; }
    public function getEventBooking(): ?EventBooking { return $this->eventBooking; }
    public function setEventBooking(?EventBooking $eventBooking): self { $this->eventBooking = $eventBooking; return $this; }
    public function getProvider(): ?PaymentProvider { return $this->provider; }
    public function setProvider(?PaymentProvider $provider): self { $this->provider = $provider; return $this; }

    /** @return Collection<int, PaymentEvent> */
    public function getEvents(): Collection { return $this->events; }
    public function addEvent(PaymentEvent $event): self { if (!$this->events->contains($event)) { $this->events[] = $event; $event->setPayment($this);} return $this; }
    public function removeEvent(PaymentEvent $event): self { if ($this->events->removeElement($event)) { if ($event->getPayment() === $this) { $event->setPayment(null); }} return $this; }

    /** @return Collection<int, Refund> */
    public function getRefunds(): Collection { return $this->refunds; }
    public function addRefund(Refund $refund): self { if (!$this->refunds->contains($refund)) { $this->refunds[] = $refund; $refund->setPayment($this); } return $this; }
    public function removeRefund(Refund $refund): self { if ($this->refunds->removeElement($refund)) { if ($refund->getPayment() === $this) { $refund->setPayment(null); }} return $this; }
}
