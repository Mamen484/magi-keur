<?php
namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\DocumentTemplateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentTemplateRepository::class)]
#[ORM\Table(name: "document_template")]
#[ApiResource]
class DocumentTemplate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length:150)]
    private ?string $name = null;

    #[ORM\Column(type: Types::STRING, length:50)]
    private ?string $type = null;

    #[ORM\Column(type: Types::STRING, length:10)]
    private ?string $locale = null;

    #[ORM\Column(type: Types::INTEGER, options:["default" => 1])]
    private int $version = 1;

    #[ORM\Column(type: Types::BOOLEAN, options:["default" => true])]
    private bool $isActive = true;

    #[ORM\ManyToOne(targetEntity: Company::class)]
    #[ORM\JoinColumn(name:"owner_company_id", referencedColumnName:"id", onDelete:"SET NULL")]
    private ?Company $ownerCompany = null;

    /**
     * @var string[]
     */
    #[ORM\Column(type: Types::JSON)]
    private array $formSchema = [];

    /**
     * @var string[]
     */
    #[ORM\Column(type: Types::JSON)]
    private array $formData = [];

    #[ORM\Column(type: Types::STRING, length:255, nullable:true)]
    private ?string $generatedPdfUrl = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(?string $name): self { $this->name = $name; return $this; }
    public function getType(): ?string { return $this->type; }
    public function setType(?string $type): self { $this->type = $type; return $this; }
    public function getLocale(): ?string { return $this->locale; }
    public function setLocale(?string $locale): self { $this->locale = $locale; return $this; }
    public function getVersion(): int { return $this->version; }
    public function setVersion(int $version): self { $this->version = $version; return $this; }
    public function isActive(): bool { return $this->isActive; }
    public function setIsActive(bool $isActive): self { $this->isActive = $isActive; return $this; }
    public function getOwnerCompany(): ?Company { return $this->ownerCompany; }
    public function setOwnerCompany(?Company $ownerCompany): self { $this->ownerCompany = $ownerCompany; return $this; }
    public function getFormSchema(): array { return $this->formSchema; }
    public function setFormSchema(array $formSchema): self { $this->formSchema = $formSchema; return $this; }
    public function getFormData(): array { return $this->formData; }
    public function setFormData(array $formData): self { $this->formData = $formData; return $this; }
    public function getGeneratedPdfUrl(): ?string { return $this->generatedPdfUrl; }
    public function setGeneratedPdfUrl(?string $generatedPdfUrl): self { $this->generatedPdfUrl = $generatedPdfUrl; return $this; }
    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): self { $this->createdAt = $createdAt; return $this; }
    public function getUpdatedAt(): ?\DateTimeImmutable { return $this->updatedAt; }
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self { $this->updatedAt = $updatedAt; return $this; }
}
