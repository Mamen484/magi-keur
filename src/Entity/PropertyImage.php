<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PropertyImageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PropertyImageRepository::class)]
#[ORM\Table(name: "property_image")]
#[ApiResource(
    normalizationContext: ['groups' => ['property_image:read']],
    denormalizationContext: ['groups' => ['property_image:write']]
)]
class PropertyImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING)]
    private string $fileName;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $url;

    #[ORM\Column(type: Types::BOOLEAN, options: ["default" => false])]
    private bool $isMain = false;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(targetEntity: Property::class, inversedBy: "images")]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Property $property = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getFileName(): string { return $this->fileName; }
    public function setFileName(string $fileName): self { $this->fileName = $fileName; return $this; }
    public function getUrl(): string { return $this->url; }
    public function setUrl(string $url): self { $this->url = $url; return $this; }
    public function isMain(): bool { return $this->isMain; }
    public function setIsMain(bool $isMain): self { $this->isMain = $isMain; return $this; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): self { $this->createdAt = $createdAt; return $this; }
    public function getProperty(): ?Property { return $this->property; }
    public function setProperty(?Property $property): self { $this->property = $property; return $this; }
}
