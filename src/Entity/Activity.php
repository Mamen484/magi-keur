<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Enum\ActivityType;
use App\Repository\ActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ActivityRepository::class)]
#[ORM\Table(name: "activity")]
#[ApiResource(
    normalizationContext: ['groups' => ['activity:read']],
    denormalizationContext: ['groups' => ['activity:write']]
)]
class Activity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(['activity:read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Groups(['activity:read', 'activity:write'])]
    private ?string $title;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['activity:read', 'activity:write'])]
    private ?string $description = null;

    #[ORM\Column(enumType: ActivityType::class)]
    #[Groups(['activity:read', 'activity:write'])]
    private ?ActivityType $type;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2, nullable: true)]
    #[Groups(['activity:read', 'activity:write'])]
    private ?string $price = null;

    #[ORM\Column(type: Types::STRING, length: 3, options: ['default' => 'EUR'])]
    #[Groups(['activity:read', 'activity:write'])]
    private ?string $currency = 'EUR';

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2, nullable: true)]
    #[Groups(['activity:read', 'activity:write'])]
    private ?string $averageRating = null;

    #[ORM\ManyToOne(inversedBy: 'activities')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Address $address = null;

    #[ORM\ManyToOne(inversedBy: 'activities')]
    private ?Company $company = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, nullable: true)]
    private ?\DateTimeInterface $deletedAt = null;

    #[ORM\OneToMany(mappedBy: 'activity', targetEntity: ActivityImage::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $images;

    #[ORM\OneToMany(mappedBy: "activity", targetEntity: ActivityCategory::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection $activityCategories;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->deletedAt = new \DateTimeImmutable();
        $this->images = new ArrayCollection();
        $this->activityCategories = new ArrayCollection();
    }

    public function getTitle(): ?string { return $this->title; }
    public function setTitle(?string $title): self { $this->title = $title; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): self { $this->description = $description; return $this; }
    public function getType(): ActivityType { return $this->type; }
    public function setType(ActivityType $type): self { $this->type = $type; return $this; }
    public function getPrice(): ?string { return $this->price; }
    public function setPrice(?string $price): self { $this->price = $price; return $this; }
    public function getCurrency(): ?string { return $this->currency; }
    public function setCurrency(?string $currency): self { $this->currency = $currency; return $this; }
    public function getAverageRating(): ?string { return $this->averageRating; }
    public function setAverageRating(?string $averageRating): self { $this->averageRating = $averageRating; return $this; }
    public function getAddress(): ?Address { return $this->address; }
    public function setAddress(?Address $address): self { $this->address = $address; return $this; }
    public function getCompany(): ?Company { return $this->company; }
    public function setCompany(?Company $company): self { $this->company = $company; return $this; }
    public function getCreatedAt(): ?\DateTimeInterface { return $this->createdAt; }
    public function setCreatedAt(\DateTimeInterface $createdAt): self { $this->createdAt = $createdAt; return $this; }
    public function getUpdatedAt(): ?\DateTimeInterface { return $this->updatedAt; }
    public function setUpdatedAt(\DateTimeInterface $updatedAt): self { $this->updatedAt = $updatedAt; return $this; }
    public function getDeletedAt(): ?\DateTimeInterface { return $this->deletedAt; }
    public function setDeletedAt(?\DateTimeInterface $deletedAt): self { $this->deletedAt = $deletedAt; return $this; }

    /** @return Collection<int, ActivityImage> */
    public function getImages(): Collection { return $this->images; }
    public function addImage(ActivityImage $image): self { if (!$this->images->contains($image)) { $this->images->add($image); $image->setActivity($this); } return $this; }
    public function removeImage(ActivityImage $image): self { if ($this->images->removeElement($image)) { if ($image->getActivity() === $this) { $image->setActivity(null); } } return $this; }

    /** @return Collection<int, ActivityCategory> */
    public function getActivityCategories(): Collection { return $this->activityCategories; }
    public function addActivityCategory(ActivityCategory $activityCategory): self { if (!$this->activityCategories->contains($activityCategory)) { $this->activityCategories->add($activityCategory); $activityCategory->setActivity($this); } return $this; }
    public function removeActivityCategory(ActivityCategory $activityCategory): self { if ($this->activityCategories->removeElement($activityCategory)) { if ($activityCategory->getActivity() === $this) { $activityCategory->setActivity(null); }} return $this; }
}
