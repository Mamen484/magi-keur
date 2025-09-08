<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Enum\SupplierType;
use App\Repository\SupplierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: SupplierRepository::class)]
#[ORM\Table(name: "supplier")]
#[ApiResource(
    normalizationContext: ['groups' => ['supplier:read']],
    denormalizationContext: ['groups' => ['supplier:write']]
)]
class Supplier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(["supplier:read"])]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 200)]
    #[Groups(["supplier:read", "supplier:write"])]
    private ?string $name = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Groups(["supplier:read", "supplier:write"])]
    private ?string $type = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id", nullable: true)]
    #[Groups(["supplier:read", "supplier:write"])]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Company::class)]
    #[ORM\JoinColumn(name: "company_id", referencedColumnName: "id", nullable: true)]
    #[Groups(["supplier:read", "supplier:write"])]
    private ?Company $company = null;

    #[ORM\OneToMany(mappedBy: "supplier", targetEntity: SupplierCategory::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection $supplierCategories;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    #[Groups(["supplier:read"])]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    #[Groups(["supplier:read"])]
    private \DateTimeImmutable $updatedAt;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, nullable: true)]
    #[Groups(["supplier:read"])]
    private ?\DateTimeImmutable $deletedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->supplierCategories = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(?string $name): self { $this->name = $name; return $this; }
    public function getType(): ?string { return $this->type; }
    public function setType(?string $type): self { $this->type = $type; return $this; }
    public function getUser(): ?User { return $this->user; }
    public function setUser(?User $user): self { $this->user = $user; return $this; }
    public function getCompany(): ?Company { return $this->company; }
    public function setCompany(?Company $company): self { $this->company = $company; return $this; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): self { $this->createdAt = $createdAt; return $this; }
    public function getUpdatedAt(): \DateTimeImmutable { return $this->updatedAt; }
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self { $this->updatedAt = $updatedAt; return $this; }
    public function getDeletedAt(): ?\DateTimeImmutable { return $this->deletedAt; }
    public function setDeletedAt(?\DateTimeImmutable $deletedAt): self { $this->deletedAt = $deletedAt; return $this; }

    /** @return Collection<int, SupplierCategory> */
    public function getSupplierCategories(): Collection { return $this->supplierCategories; }
    public function addSupplierCategory(SupplierCategory $supplierCategory): self { if (!$this->supplierCategories->contains($supplierCategory)) { $this->supplierCategories->add($supplierCategory); $supplierCategory->setSupplier($this); } return $this; }
    public function removeSupplierCategory(SupplierCategory $supplierCategory): self { if ($this->supplierCategories->removeElement($supplierCategory)) { if ($supplierCategory->getSupplier() === $this) { $supplierCategory->setSupplier(null); }} return $this; }
}
