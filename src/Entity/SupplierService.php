<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SupplierServiceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: SupplierServiceRepository::class)]
#[ORM\Table(name: "supplier_service")]
#[ApiResource(
    normalizationContext: ['groups' => ['service:read']],
    denormalizationContext: ['groups' => ['service:write']]
)]
class SupplierService
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(["service:read"])]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Groups(["service:read", "service:write"])]
    private ?string $title = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2, nullable: true)]
    #[Groups(["service:read", "service:write"])]
    private ?string $price = null;

    #[ORM\Column(type: Types::STRING, length: 3, options: ["default" => "EUR"])]
    #[Groups(["service:read", "service:write"])]
    private string $currency = "EUR";

    #[ORM\ManyToOne(targetEntity: Supplier::class)]
    #[ORM\JoinColumn(name: "supplier_id", referencedColumnName: "id", nullable: true)]
    #[Groups(["service:read", "service:write"])]
    private ?Supplier $supplier = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    #[Groups(["service:read"])]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    #[Groups(["service:read"])]
    private \DateTimeImmutable $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getTitle(): ?string { return $this->title; }
    public function setTitle(?string $title): self { $this->title = $title; return $this; }
    public function getPrice(): ?string { return $this->price; }
    public function setPrice(?string $price): self { $this->price = $price; return $this; }
    public function getCurrency(): string { return $this->currency; }
    public function setCurrency(string $currency): self { $this->currency = $currency; return $this; }
    public function getSupplier(): ?Supplier { return $this->supplier; }
    public function setSupplier(?Supplier $supplier): self { $this->supplier = $supplier; return $this; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): self { $this->createdAt = $createdAt; return $this; }
    public function getUpdatedAt(): \DateTimeImmutable { return $this->updatedAt; }
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self { $this->updatedAt = $updatedAt; return $this; }
}
