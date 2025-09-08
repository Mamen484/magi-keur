<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\Table(name: "category")]
#[ApiResource(
    normalizationContext: ['groups' => ['category:read']],
    denormalizationContext: ['groups' => ['category:write']]
)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(["category:read"])]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 120)]
    #[Groups(["category:read", "category:write"])]
    private ?string $name = null;

    #[ORM\Column(type: Types::STRING, length: 140, unique: true)]
    #[Groups(["category:read", "category:write"])]
    private ?string $slug = null;

    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(name: "parent_id", referencedColumnName: "id", onDelete: "SET NULL", nullable: true)]
    #[Groups(["category:read", "category:write"])]
    private ?Category $parent = null;

    #[ORM\Column(type: Types::INTEGER, options: ["default" => 0])]
    #[Groups(["category:read", "category:write"])]
    private int $visibilityBoost = 0;

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(?string $name): self { $this->name = $name; return $this; }
    public function getSlug(): ?string { return $this->slug; }
    public function setSlug(?string $slug): self { $this->slug = $slug; return $this; }
    public function getParent(): ?Category { return $this->parent; }
    public function setParent(?Category $parent): self { $this->parent = $parent; return $this; }
    public function getVisibilityBoost(): int { return $this->visibilityBoost; }
    public function setVisibilityBoost(int $visibilityBoost): self { $this->visibilityBoost = $visibilityBoost; return $this; }
}
