<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\WebhookLogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: WebhookLogRepository::class)]
#[ORM\Table(name: "webhook_log")]
#[ApiResource(
    normalizationContext: ['groups' => ['webhook:read']],
    denormalizationContext: ['groups' => ['webhook:write']]
)]
class WebhookLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(["webhook:read"])]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING,length: 255)]
    #[Groups(["webhook:read","webhook:write"])]
    private ?string $url = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    #[Groups(["webhook:read","webhook:write"])]
    private ?array $payloadJson = null;

    #[ORM\Column(type: Types::STRING, length: 10, nullable: true)]
    #[Groups(["webhook:read","webhook:write"])]
    private ?string $responseStatus = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Groups(["webhook:read"])]
    private \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getUrl(): ?string { return $this->url; }
    public function setUrl(string $url): self { $this->url = $url; return $this; }
    public function getPayloadJson(): ?array { return $this->payloadJson; }
    public function setPayloadJson(?array $payloadJson): self { $this->payloadJson = $payloadJson; return $this; }
    public function getResponseStatus(): ?string { return $this->responseStatus; }
    public function setResponseStatus(?string $responseStatus): self { $this->responseStatus = $responseStatus; return $this; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): self { $this->createdAt = $createdAt; return $this; }
}
