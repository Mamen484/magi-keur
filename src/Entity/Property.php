<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;
use App\Controller\PropertyPdfController;
use App\Enum\PropertyStatus;
use App\Enum\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PropertyRepository::class)]
#[ORM\Table(name: "property")]
#[ApiResource(
    normalizationContext: ['groups' => ['property:read']],
    denormalizationContext: ['groups' => ['property:write']],
    operations: [
        new Get(),
        new GetCollection(),
        new Get(
            uriTemplate: '/properties/{id}/generate-pdf',
            controller: PropertyPdfController::class,
            name: 'generate_property_pdf',
            extraProperties:[
                'openapiContext' => [
                    'summary' => 'Génère un PDF de la fiche propriété',
                    'description' => 'Génère un document PDF contenant les détails de la propriété'
                ],
            ],
            read: false,
            output: false
        )
    ]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'type' => 'exact',
    'city' => 'partial',
    'status' => 'exact',
    'isVisible' => 'exact',
])]
#[ApiFilter(RangeFilter::class, properties: ['price', 'surface'])]
class Property
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(['property:read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Groups(['property:read', 'property:write'])]
    private string $title;

    #[ORM\Column(type: Types::TEXT, nullable:true)]
    #[Groups(['property:read', 'property:write'])]
    private ?string $description = null;

    #[ORM\Column(enumType: PropertyType::class)]
    #[Groups(['property:read', 'property:write'])]
    private PropertyType $type;

    #[ORM\Column(enumType: PropertyStatus::class)]
    #[Groups(['property:read', 'property:write'])]
    private PropertyStatus $status;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2)]
    #[Groups(['property:read', 'property:write'])]
    private string $price;

    #[ORM\Column(type: Types::STRING, length: 3, options: ["default" => "EUR"])]
    #[Groups(['property:read', 'property:write'])]
    private string $currency = 'EUR';

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2)]
    #[Groups(['property:read', 'property:write'])]
    private string $surface;

    #[ORM\Column(type: Types::BOOLEAN, options: ["default" => true])]
    #[Groups(['property:read', 'property:write'])]
    private bool $isVisible = true;


    #[ORM\Column(type: "datetimetz_immutable")]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private \DateTimeImmutable $updatedAt;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    #[ORM\ManyToOne(targetEntity: Address::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: "RESTRICT")]
    private Address $address;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: "RESTRICT")]
    private User $owner;

    #[ORM\ManyToOne(targetEntity: Company::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: "RESTRICT")]
    private Company $company;

    #[ORM\OneToMany(mappedBy: "property", targetEntity: PropertyImage::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection $images;

    #[ORM\OneToMany(mappedBy: "property", targetEntity: PropertyCategory::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection $propertyCategories;

    #[ORM\OneToMany(mappedBy: "property", targetEntity: Lease::class, cascade: ["persist", "remove"])]
    private Collection $leases;

    public function __construct() {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->images = new ArrayCollection();
        $this->propertyCategories = new ArrayCollection();
        $this->leases = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getTitle(): string { return $this->title; }
    public function setTitle(string $title): self { $this->title = $title; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): self { $this->description = $description; return $this; }
    public function getType(): PropertyType { return $this->type; }
    public function setType(PropertyType $type): self { $this->type = $type; return $this; }
    public function getStatus(): PropertyStatus { return $this->status; }
    public function setStatus(PropertyStatus $status): self { $this->status = $status; return $this; }
    public function getPrice(): string { return $this->price; }
    public function setPrice(string $price): self { $this->price = $price; return $this; }
    public function getCurrency(): string { return $this->currency; }
    public function setCurrency(string $currency): self { $this->currency = $currency; return $this; }
    public function getSurface(): string { return $this->surface; }
    public function setSurface(string $surface): self { $this->surface = $surface; return $this; }
    public function isVisible(): bool { return $this->isVisible; }
    public function setIsVisible(bool $isVisible): self { $this->isVisible = $isVisible; return $this; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): self { $this->createdAt = $createdAt; return $this; }
    public function getUpdatedAt(): \DateTimeImmutable { return $this->updatedAt; }
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self { $this->updatedAt = $updatedAt; return $this; }
    public function getDeletedAt(): ?\DateTimeImmutable { return $this->deletedAt; }
    public function setDeletedAt(?\DateTimeImmutable $deletedAt): self { $this->deletedAt = $deletedAt; return $this; }
    public function getAddress(): Address { return $this->address; }
    public function setAddress(Address $address): self { $this->address = $address; return $this; }
    public function getOwner(): User { return $this->owner; }
    public function setOwner(User $owner): self { $this->owner = $owner; return $this; }
    public function getCompany(): Company { return $this->company; }
    public function setCompany(Company $company): self { $this->company = $company; return $this; }

    /** @return Collection<int, PropertyImage> */
    public function getImages(): Collection { return $this->images; }
    public function addImage(PropertyImage $image): self { if(!$this->images->contains($image)) { $this->images[] = $image; $image->setProperty($this);} return $this; }
    public function removeImage(PropertyImage $image): self { if($this->images->removeElement($image)) { if($image->getProperty() === $this) $image->setProperty(null);} return $this; }

    /** @return Collection<int, PropertyCategory> */
    public function getPropertyCategories(): Collection { return $this->propertyCategories; }
    public function addPropertyCategory(PropertyCategory $propertyCategory): self { if (!$this->propertyCategories->contains($propertyCategory)) { $this->propertyCategories[] = $propertyCategory; $propertyCategory->setProperty($this);} return $this; }
    public function removePropertyCategory(PropertyCategory $propertyCategory): self { if ($this->propertyCategories->removeElement($propertyCategory)) { if ($propertyCategory->getProperty() === $this) { $propertyCategory->setProperty(null);}} return $this; }

    /** @return Collection<int, Lease> */
    public function getLeases(): Collection { return $this->leases; }
    public function addLease(Lease $lease): self { if (!$this->leases->contains($lease)) { $this->leases[] = $lease; $lease->setProperty($this); } return $this; }
    public function removeLease(Lease $lease): self { if ($this->leases->removeElement($lease)) { if ($lease->getProperty() === $this) { $lease->setProperty(null); }} return $this; }

    public function getShortDescription(int $limit = 50): string {
        if(!$this->description) return '';
        $text = strip_tags($this->description);
        return mb_strlen($text) > $limit ? mb_substr($text, 0, $limit) . '...' : $text;
    }
}
