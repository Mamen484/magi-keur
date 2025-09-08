<?php

namespace App\Entity;

use App\Repository\WebhookEndpointRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WebhookEndpointRepository::class)]
#[ORM\Table(name: "webhook_endpoint")]
class WebhookEndpoint
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Company::class)]
    #[ORM\JoinColumn(name:"company_id", referencedColumnName:"id", nullable:false, onDelete:"CASCADE")]
    private ?Company $company = null;

    #[ORM\Column(type: Types::STRING, length:255)]
    private ?string $url = null;

    #[ORM\Column(type: Types::STRING, length:255, nullable:true)]
    private ?string $secret = null;

    #[ORM\Column(type: Types::BOOLEAN, options:["default" => true])]
    private bool $active = true;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable(); $this->active = true;
    }

    public function getId(): ?int { return $this->id; }
    public function getCompany(): ?Company { return $this->company; }
    public function setCompany(?Company $company): self { $this->company = $company; return $this; }
    public function getUrl(): ?string { return $this->url; }
    public function setUrl(string $url): self { $this->url = $url; return $this; }
    public function getSecret(): ?string { return $this->secret; }
    public function setSecret(?string $secret): self { $this->secret = $secret; return $this; }
    public function isActive(): bool { return $this->active; }
    public function setActive(bool $active): self { $this->active = $active; return $this; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): self { $this->createdAt = $createdAt; return $this; }
}
