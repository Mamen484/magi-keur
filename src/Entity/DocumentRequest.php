<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\DocumentRequestPdfController;
use App\Repository\DocumentRequestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentRequestRepository::class)]
#[ORM\Table(name: "document_request")]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Get(
            uriTemplate: '/legal_document_requests/{id}/generate-pdf',
            controller: DocumentRequestPdfController::class,
            name: 'generate_legal_document_pdf',
            extraProperties:[
                'openapiContext' => [
                    'summary' => 'Génère un PDF pour une demande de document juridique',
                    'description' => 'Renvoie un fichier PDF généré à partir de la LegalDocumentRequest'
                ],
            ],
            defaults: ['_api_receive' => false],
            read: false,
            output: false
        )
    ]
)]
class DocumentRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name:"user_id", referencedColumnName:"id", nullable:false, onDelete:"CASCADE")]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: DocumentTemplate::class)]
    #[ORM\JoinColumn(name:"template_id", referencedColumnName:"id", onDelete:"SET NULL")]
    private ?DocumentTemplate $template = null;

    /**
     * @var string[]
     */
    #[ORM\Column(type: Types::JSON)]
    private array $formData = [];

    #[ORM\Column(type: Types::STRING, length:255, nullable:true)]
    private ?string $generatedPdfUrl = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int { return $this->id; }
    public function getUser(): ?User { return $this->user; }
    public function setUser(?User $user): self { $this->user = $user; return $this; }
    public function getTemplate(): ?DocumentTemplate { return $this->template; }
    public function setTemplate(?DocumentTemplate $template): self { $this->template = $template; return $this; }
    public function getFormData(): array { return $this->formData; }
    public function setFormData(array $formData): self { $this->formData = $formData; return $this; }
    public function getGeneratedPdfUrl(): ?string { return $this->generatedPdfUrl; }
    public function setGeneratedPdfUrl(?string $generatedPdfUrl): self { $this->generatedPdfUrl = $generatedPdfUrl; return $this; }
    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): self { $this->createdAt = $createdAt; return $this; }
}
