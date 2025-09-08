<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'user_hub')]
#[ApiResource(
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:write']]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    final public const ROLE_USER = 'ROLE_USER';
    final public const ROLE_ADMIN = 'ROLE_ADMIN';
    final public const ROLE_PRO = 'ROLE_PRO';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(["user:read"])]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    #[Groups(["user:read", "user:write"])]
    private ?string $fullName = null;

    #[ORM\Column(type: Types::STRING, length:50, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 50)]
    #[Groups(["user:read", "user:write"])]
    private ?string $username = null;

    #[ORM\Column(type: Types::STRING, length: 180, unique: true)]
    #[Assert\Email]
    #[Groups(["user:read", "user:write"])]
    private ?string $email = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Groups(["user:write"])]
    private ?string $password = null;

    #[ORM\Column(type: Types::STRING, unique: false, nullable: true)]
    #[Groups(["user:read", "user:write"])]
    private ?string $phone = null;

    /**
     * @var string[]
     */
    #[ORM\Column(type: Types::JSON)]
    #[Groups(["user:read", "user:write"])]
    private array $roles = [];

    #[ORM\Column(type: Types::STRING, length: 10, options: ["default" => "fr_FR"])]
    #[Groups(["user:read", "user:write"])]
    private ?string $locale = 'fr_FR';

    #[ORM\ManyToOne(targetEntity: Address::class)]
    #[ORM\JoinColumn(name: "address_id", referencedColumnName: "id", onDelete: "SET NULL", nullable: true)]
    #[Groups(["user:read", "user:write"])]
    private ?Address $address = null;

    #[ORM\ManyToOne(targetEntity: Company::class)]
    #[ORM\JoinColumn(name: "company_id", referencedColumnName: "id", onDelete: "SET NULL", nullable: true)]
    #[Groups(["user:read", "user:write"])]
    private ?Company $company = null;

    #[ORM\OneToMany(mappedBy: "reviewer", targetEntity: Review::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection $reviews;

    #[ORM\OneToMany(mappedBy: "organizer", targetEntity: Event::class)]
    private Collection $organizedEvents;

    #[ORM\OneToMany(mappedBy: "user", targetEntity: CalendarSyncAccount::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection $calendarSyncAccounts;

    #[ORM\OneToMany(mappedBy: "tenant", targetEntity: RentPayment::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection $rentPayments;

    #[ORM\OneToMany(mappedBy: "client", targetEntity: Invoice::class)]
    private Collection $invoicesAsClient;

    #[ORM\OneToMany(mappedBy: "provider", targetEntity: Invoice::class)]
    private Collection $invoicesAsProvider;

    #[ORM\OneToMany(mappedBy: "booker", targetEntity: EventBooking::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection $eventBookings;

    #[ORM\OneToMany(mappedBy: "tenant", targetEntity: Lease::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection $leases;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    #[Groups(["user:read"])]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    #[Groups(["user:read"])]
    private \DateTimeImmutable $updatedAt;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, nullable: true)]
    #[Groups(["user:read"])]
    private ?\DateTimeImmutable $deletedAt = null;

    public function __construct()
    {
        $this->roles = [];
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->reviews = new ArrayCollection();
        $this->organizedEvents = new ArrayCollection();
        $this->calendarSyncAccounts = new ArrayCollection();
        $this->rentPayments = new ArrayCollection();
        $this->invoicesAsClient = new ArrayCollection();
        $this->invoicesAsProvider = new ArrayCollection();
        $this->eventBookings = new ArrayCollection();
        $this->leases = new ArrayCollection();
    }

    // basic getters/setters
    public function getId(): ?int { return $this->id; }
    public function getFullName(): ?string { return $this->fullName; }
    public function setFullName(?string $fullName): self { $this->fullName = $fullName; return $this; }
    public function getUsername(): ?string { return $this->username; }
    public function setUsername(?string $username): self { $this->username = $username; return $this; }
    public function getUserIdentifier(): string { return (string) $this->username; }
    public function getEmail(): ?string { return $this->email; }
    public function setEmail(?string $email): self { $this->email = $email; return $this; }
    public function getPassword(): ?string { return $this->password; }
    public function setPassword(?string $password): self { $this->password = $password; return $this; }
    public function getPhone(): ?string { return $this->phone; }
    public function setPhone(?string $phone): self { $this->phone = $phone; return $this; }
    public function getLocale(): ?string { return $this->locale; }
    public function setLocale(?string $locale): self { $this->locale = $locale; return $this; }
    public function getAddress(): ?Address { return $this->address; }
    public function setAddress(?Address $address): self { $this->address = $address; return $this; }
    public function getCompany(): ?Company { return $this->company; }
    public function setCompany(?Company $company): self { $this->company = $company; return $this; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): self { $this->createdAt = $createdAt; return $this; }
    public function getUpdatedAt(): \DateTimeImmutable { return $this->updatedAt; }
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self { $this->updatedAt = $updatedAt; return $this; }
    public function getDeletedAt(): ?\DateTimeImmutable { return $this->deletedAt; }
    public function setDeletedAt(?\DateTimeImmutable $deletedAt): self { $this->deletedAt = $deletedAt; return $this; }

    // Returns the roles or permissions granted to the user for security.
    public function getRoles(): array { $roles = $this->roles; if (empty($roles)) { $roles[] = self::ROLE_USER; } return array_unique($roles); }
    /**
     * @param string[] $roles
     */
    public function setRoles(array $roles): self { $this->roles = $roles; return $this; }

    /** @return Collection<int, Review> */
    public function getReviews(): Collection { return $this->reviews; }
    public function addReview(Review $review): self { if (!$this->reviews->contains($review)) { $this->reviews[] = $review; $review->setReviewer($this);} return $this; }
    public function removeReview(Review $review): self { if ($this->reviews->removeElement($review)) { if ($review->getReviewer() === $this) { $review->setReviewer(null); }} return $this; }

    /** @return Collection<int, Event> */
    public function getOrganizedEvents(): Collection { return $this->organizedEvents; }
    public function addOrganizedEvent(Event $event): self { if (!$this->organizedEvents->contains($event)) { $this->organizedEvents[] = $event; $event->setOrganizer($this); } return $this; }
    public function removeOrganizedEvent(Event $event): self { if ($this->organizedEvents->removeElement($event)) { if ($event->getOrganizer() === $this) { $event->setOrganizer(null); }} return $this; }

    /** @return Collection<int, CalendarSyncAccount> */
    public function getCalendarSyncAccounts(): Collection { return $this->calendarSyncAccounts; }
    public function addCalendarSyncAccount(CalendarSyncAccount $account): self { if (!$this->calendarSyncAccounts->contains($account)) { $this->calendarSyncAccounts[] = $account; $account->setUser($this); } return $this; }
    public function removeCalendarSyncAccount(CalendarSyncAccount $account): self { if ($this->calendarSyncAccounts->removeElement($account)) { if ($account->getUser() === $this) { $account->setUser(null); }} return $this; }

    /** @return Collection<int, RentPayment> */
    public function getRentPayments(): Collection { return $this->rentPayments; }
    public function addRentPayment(RentPayment $payment): self { if (!$this->rentPayments->contains($payment)) { $this->rentPayments[] = $payment; $payment->setTenant($this); } return $this; }
    public function removeRentPayment(RentPayment $payment): self { if ($this->rentPayments->removeElement($payment)) { if ($payment->getTenant() === $this) { $payment->setTenant($this); }} return $this; }

    /** @return Collection<int, Invoice> */
    public function getInvoicesAsClient(): Collection { return $this->invoicesAsClient; }
    public function addInvoiceAsClient(Invoice $invoice): self { if (!$this->invoicesAsClient->contains($invoice)) { $this->invoicesAsClient[] = $invoice; $invoice->setClient($this); } return $this; }
    public function removeInvoiceAsClient(Invoice $invoice): self { if ($this->invoicesAsClient->removeElement($invoice)) { if ($invoice->getClient() === $this) { $invoice->setClient(null); }} return $this; }

    /** @return Collection<int, Invoice> */
    public function getInvoicesAsProvider(): Collection { return $this->invoicesAsProvider; }
    public function addInvoiceAsProvider(Invoice $invoice): self { if (!$this->invoicesAsProvider->contains($invoice)) { $this->invoicesAsProvider[] = $invoice; $invoice->setProvider($this); } return $this; }
    public function removeInvoiceAsProvider(Invoice $invoice): self { if ($this->invoicesAsProvider->removeElement($invoice)) { if ($invoice->getProvider() === $this) { $invoice->setProvider(null); }} return $this; }

    /** @return Collection<int, EventBooking> */
    public function getEventBookings(): Collection { return $this->eventBookings; }
    public function addEventBooking(EventBooking $booking): self { if (!$this->eventBookings->contains($booking)) { $this->eventBookings[] = $booking; $booking->setBooker($this); } return $this; }
    public function removeEventBooking(EventBooking $booking): self { if ($this->eventBookings->removeElement($booking)) { if ($booking->getBooker() === $this) { $booking->setBooker(null); }} return $this; }

    /** @return Collection<int, Lease> */
    public function getLeases(): Collection { return $this->leases; }
    public function addLease(Lease $lease): self { if (!$this->leases->contains($lease)) { $this->leases[] = $lease; $lease->setTenant($this); } return $this; }
    public function removeLease(Lease $lease): self { if ($this->leases->removeElement($lease)) { if ($lease->getTenant() === $this) { $lease->setTenant(null); }} return $this; }

    /**
     * Removes sensitive data from the user.
     * if you had a plainPassword property, you'd nullify it here
     * {@inheritdoc}
     */
    public function eraseCredentials(): void { /* $this->plainPassword = null; */ }

    /**
     * @return array{int|null, string|null, string|null}
     */
    public function __serialize(): array { return [$this->username, $this->password]; }

    /**
     * @param array{int|null, string, string} $data
     */
    public function __unserialize(array $data): void { [$this->username, $this->password] = $data; }
}
