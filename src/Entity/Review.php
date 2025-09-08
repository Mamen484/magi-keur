<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Enum\ReviewableType;
use App\Repository\ReviewRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
#[ORM\Table(name: "review")]
#[ORM\UniqueConstraint(name: "ux_review_res_reviewer", columns: ["reservation_id", "reviewer_id"])]
#[ApiResource(
    normalizationContext: ['groups' => ['review:read']],
    denormalizationContext: ['groups' => ['review:write']]
)]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(['review:read'])]
    private ?int $id = null;

    #[ORM\Column(enumType: ReviewableType::class)]
    #[Groups(['property:read', 'property:write'])]
    private ReviewableType $type;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $reviewableId = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups(['review:read', 'review:write'])]
    private ?int $rating = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['review:read', 'review:write'])]
    private ?string $comment = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $reviewer = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: "SET NULL")]
    private ?Reservation $reservation = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Groups(['review:read', 'review:write'])]
    private ?\DateTimeInterface $createdAt = null;

    public function __construct() { $this->createdAt = new \DateTimeImmutable(); }

    public function getId(): ?int { return $this->id; }
    public function getType(): ReviewableType { return $this->type; }
    public function setType(ReviewableType $type): self { $this->type = $type; return $this; }
    public function getReviewableId(): ?int { return $this->reviewableId; }
    public function setReviewableId(int $reviewableId): self { $this->reviewableId = $reviewableId; return $this; }
    public function getRating(): ?int { return $this->rating; }
    public function setRating(int $rating): self { $this->rating = $rating; return $this; }
    public function getComment(): ?string { return $this->comment; }
    public function setComment(?string $comment): self { $this->comment = $comment; return $this; }
    public function getCreatedAt(): ?\DateTimeInterface { return $this->createdAt; }
    public function setCreatedAt(\DateTimeInterface $createdAt): self { $this->createdAt = $createdAt; return $this; }
    public function getReviewer(): ?User { return $this->reviewer; }
    public function setReviewer(?User $reviewer): self { $this->reviewer = $reviewer; return $this; }
    public function getReservation(): ?Reservation { return $this->reservation; }
    public function setReservation(?Reservation $reservation): self { $this->reservation = $reservation; return $this; }
}
