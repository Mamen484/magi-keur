<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ServiceAreaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ServiceAreaRepository::class)]
#[ORM\Table(name: "service_area")]
#[ApiResource(
    normalizationContext: ['groups' => ['service_area:read']],
    denormalizationContext: ['groups' => ['service_area:write']],
)]
class ServiceArea
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(["service_area:read"])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Supplier::class)]
    #[ORM\JoinColumn(name: "supplier_id", referencedColumnName: "id", onDelete: "CASCADE")]
    #[Groups(["service_area:read", "service_area:write"])]
    private ?Supplier $supplier = null;

    #[ORM\Column(type: "polygon", nullable: true)]
    private ?array $polygon = null;

    #[ORM\Column(type: Types::STRING, length: 120, nullable: true)]
    #[Groups(["service_area:read", "service_area:write"])]
    private ?string $city = null;

    #[ORM\Column(type: Types::STRING, length: 2, nullable: true)]
    #[Groups(["service_area:read", "service_area:write"])]
    private ?string $countryCode = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    #[Groups(["service_area:read"])]
    private \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getSupplier(): ?Supplier { return $this->supplier; }
    public function setSupplier(?Supplier $supplier): self { $this->supplier = $supplier; return $this; }
    public function getPolygon(): ?array { return $this->polygon; }
    public function setPolygon(?array $polygon): self { $this->polygon = $polygon; return $this; }
    public function getCity(): ?string { return $this->city; }
    public function setCity(?string $city): self { $this->city = $city; return $this; }
    public function getCountryCode(): ?string { return $this->countryCode; }
    public function setCountryCode(?string $countryCode): self { $this->countryCode = $countryCode; return $this; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): self { $this->createdAt = $createdAt; return $this; }
}
