<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "payment_account")]
class PaymentAccount
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id", onDelete: "SET NULL", nullable: true)]
    private ?User $user = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: "company_id", referencedColumnName: "id", onDelete: "SET NULL", nullable: true)]
    private ?Company $company = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: "provider_id", referencedColumnName: "id", onDelete: "RESTRICT", nullable: false)]
    private PaymentProvider $provider;

    #[ORM\Column(type: Types::STRING, length: 120, nullable: true)]
    private ?string $externalAccountId = null;

    #[ORM\Column(type: "datetime_immutable")]
    private \DateTimeImmutable $createdAt;

    public function __construct(PaymentProvider $provider, ?User $user = null, ?Company $company = null, ?string $externalAccountId = null) {
        $this->provider = $provider;
        $this->user = $user;
        $this->company = $company;
        $this->externalAccountId = $externalAccountId;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getUser(): ?User { return $this->user; }
    public function setUser(?User $user): self { $this->user = $user; return $this; }
    public function getCompany(): ?Company { return $this->company; }
    public function setCompany(?Company $company): self { $this->company = $company; return $this; }
    public function getProvider(): PaymentProvider { return $this->provider; }
    public function setProvider(PaymentProvider $provider): self { $this->provider = $provider; return $this; }
    public function getExternalAccountId(): ?string { return $this->externalAccountId; }
    public function setExternalAccountId(?string $externalAccountId): self { $this->externalAccountId = $externalAccountId; return $this; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
}
