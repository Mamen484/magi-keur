<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CalendarSyncAccountRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CalendarSyncAccountRepository::class)]
#[ORM\Table(name: "calendar_sync_account")]
#[ApiResource]
class CalendarSyncAccount
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'calendarSyncAccounts')]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?User $user = null;

    #[ORM\Column(type: Types::STRING, length: 40)]
    private ?string $provider = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $accessToken = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $refreshToken = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $scope = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, nullable: true)]
    private ?\DateTimeInterface $syncedAt = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->syncedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getUser(): ?User { return $this->user; }
    public function setUser(?User $user): self { $this->user = $user; return $this; }
    public function getProvider(): ?string { return $this->provider; }
    public function setProvider(?string $provider): self { $this->provider = $provider; return $this; }
    public function getAccessToken(): ?string { return $this->accessToken; }
    public function setAccessToken(string $accessToken): self { $this->accessToken = $accessToken; return $this; }
    public function getRefreshToken(): ?string { return $this->refreshToken; }
    public function setRefreshToken(?string $refreshToken): self { $this->refreshToken = $refreshToken; return $this; }
    public function getScope(): ?string { return $this->scope; }
    public function setScope(?string $scope): self { $this->scope = $scope; return $this; }
    public function getSyncedAt(): ?\DateTimeInterface { return $this->syncedAt; }
    public function setSyncedAt(?\DateTimeInterface $syncedAt): self { $this->syncedAt = $syncedAt; return $this; }
    public function getCreatedAt(): ?\DateTimeInterface { return $this->createdAt; }
    public function setCreatedAt(\DateTimeInterface $createdAt): self { $this->createdAt = $createdAt; return $this; }
}
