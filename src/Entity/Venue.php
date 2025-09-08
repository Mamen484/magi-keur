<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Enum\VenueType;
use App\Repository\VenueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VenueRepository::class)]
#[ORM\Table(name: "venue")]
#[ApiResource(
    normalizationContext: ['groups' => ['venue:read']],
    denormalizationContext: ['groups' => ['venue:write']]
)]
class Venue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(['venue:read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 200)]
    #[Groups(['venue:read', 'venue:write'])]
    private string $name;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['venue:read', 'venue:write'])]
    private ?string $description = null;

    #[ORM\Column(enumType: VenueType::class)]
    #[Groups(['venue:read', 'venue:write'])]
    private VenueType $type;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    #[Groups(['venue:read', 'venue:write'])]
    private ?int $capacity = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2, nullable: true)]
    #[Groups(['venue:read', 'venue:write'])]
    private ?string $pricePerHour = null;

    #[ORM\Column(type: Types::STRING, length: 3)]
    #[Groups(['venue:read', 'venue:write'])]
    private string $currency = 'EUR';

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'owner_id', referencedColumnName: 'id')]
    #[Groups(['venue:read', 'venue:write'])]
    private ?User $owner = null;

    #[ORM\ManyToOne(targetEntity: Address::class)]
    #[ORM\JoinColumn(name: 'address_id', referencedColumnName: 'id')]
    #[Groups(['venue:read', 'venue:write'])]
    private ?Address $address = null;

    #[ORM\ManyToOne(targetEntity: Company::class)]
    #[ORM\JoinColumn(name: 'company_id', referencedColumnName: 'id', nullable: false)]
    #[Groups(['venue:read', 'venue:write'])]
    private Company $company;

    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'now()'])]
    #[Groups(['venue:read'])]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'now()'])]
    #[Groups(['venue:read'])]
    private \DateTimeImmutable $updatedAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Groups(['venue:read'])]
    private ?\DateTimeImmutable $deletedAt = null;

    #[ORM\OneToMany(mappedBy: 'venue', targetEntity: VenueImage::class, cascade: ['persist', 'remove'])]
    #[Groups(['venue:read', 'venue:write'])]
    private iterable $images;

    #[ORM\OneToMany(mappedBy: "venue", targetEntity: Event::class)]
    private Collection $events;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->deletedAt = new \DateTimeImmutable();
        $this->images = new ArrayCollection();
        $this->events = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): self { $this->description = $description; return $this; }
    public function getType(): VenueType { return $this->type; }
    public function setType(VenueType $type): self { $this->type = $type; return $this; }
    public function getCapacity(): ?int { return $this->capacity; }
    public function setCapacity(?int $capacity): self { $this->capacity = $capacity; return $this; }
    public function getPricePerHour(): ?string { return $this->pricePerHour; }
    public function setPricePerHour(?string $pricePerHour): self { $this->pricePerHour = $pricePerHour; return $this; }
    public function getCurrency(): string { return $this->currency; }
    public function setCurrency(string $currency): self { $this->currency = $currency; return $this; }
    public function getOwner(): ?User { return $this->owner; }
    public function setOwner(?User $owner): self { $this->owner = $owner; return $this; }
    public function getAddress(): ?Address { return $this->address; }
    public function setAddress(?Address $address): self { $this->address = $address; return $this; }
    public function getCompany(): Company { return $this->company; }
    public function setCompany(Company $company): self { $this->company = $company; return $this; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): self { $this->createdAt = $createdAt; return $this; }
    public function getUpdatedAt(): \DateTimeImmutable { return $this->updatedAt; }
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self { $this->updatedAt = $updatedAt; return $this; }
    public function getDeletedAt(): ?\DateTimeImmutable { return $this->deletedAt; }
    public function setDeletedAt(?\DateTimeImmutable $deletedAt): self { $this->deletedAt = $deletedAt; return $this; }
    public function getImages(): iterable { return $this->images; }
    public function setImages(iterable $images): self { $this->images = $images; return $this; }

    /** @return Collection<int, Event> */
    public function getEvents(): Collection { return $this->events; }
    public function addEvent(Event $event): self { if (!$this->events->contains($event)) { $this->events[] = $event; $event->setVenue($this); } return $this; }
    public function removeEvent(Event $event): self { if ($this->events->removeElement($event)) { if ($event->getVenue() === $this) { $event->setVenue(null); }} return $this; }
}
