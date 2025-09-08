<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Enum\SubscriptionStatus;
use App\Repository\SubscriptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SubscriptionRepository::class)]
#[ORM\Table(name: "subscription")]
#[ApiResource(
    normalizationContext: ['groups' => ['subscription:read']],
    denormalizationContext: ['groups' => ['subscription:write']]
)]
class Subscription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(['subscription:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: "company_id", referencedColumnName: "id", onDelete: "CASCADE", nullable: false)]
    #[Groups(['subscription:read', 'subscription:write'])]
    private Company $company;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: "plan_id", referencedColumnName: "id", nullable: false)]
    #[Groups(['subscription:read', 'subscription:write'])]
    private SubscriptionPlan $plan;

    #[ORM\Column(type: Types::STRING, length: 3, options: ["default" => "EUR"])]
    #[Groups(['subscription:read', 'subscription:write'])]
    private string $currency = 'EUR';

    #[ORM\Column(type: Types::STRING, length: 20, options: ["default" => "month"])]
    #[Groups(['subscription:read', 'subscription:write'])]
    private string $interval = 'month';

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    #[Groups(['subscription:read', 'subscription:write'])]
    private \DateTimeImmutable $startedAt;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    #[Groups(['subscription:read', 'subscription:write'])]
    private \DateTimeImmutable $endsAt;

    #[ORM\Column(enumType: SubscriptionStatus::class)]
    #[Groups(['subscription:read', 'subscription:write'])]
    private ?SubscriptionStatus $status = SubscriptionStatus::PENDING;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    #[Groups(['subscription:read', 'subscription:write'])]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    #[Groups(['subscription:read', 'subscription:write'])]
    private \DateTimeImmutable $updatedAt;

    public function __construct(Company $company, SubscriptionPlan $plan, SubscriptionStatus $status, \DateTimeImmutable $startedAt, \DateTimeImmutable $endsAt, string $currency = 'EUR', string $interval = 'month')
    {
        $this->company = $company;
        $this->plan = $plan;
        $this->status = $status;
        $this->startedAt = $startedAt;
        $this->endsAt = $endsAt;
        $this->currency = $currency;
        $this->interval = $interval;
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getCompany(): Company { return $this->company; }
    public function setCompany(Company $company): self { $this->company = $company; return $this; }
    public function getPlan(): SubscriptionPlan { return $this->plan; }
    public function setPlan(SubscriptionPlan $plan): self { $this->plan = $plan; return $this; }
    public function getCurrency(): string { return $this->currency; }
    public function setCurrency(string $currency): self { $this->currency = $currency; return $this; }
    public function getInterval(): string { return $this->interval; }
    public function setInterval(string $interval): self { $this->interval = $interval; return $this; }
    public function getStartedAt(): \DateTimeImmutable { return $this->startedAt; }
    public function setStartedAt(\DateTimeImmutable $startedAt): self { $this->startedAt = $startedAt; return $this; }
    public function getEndsAt(): \DateTimeImmutable { return $this->endsAt; }
    public function setEndsAt(\DateTimeImmutable $endsAt): self { $this->endsAt = $endsAt; return $this; }
    public function getStatus(): SubscriptionStatus { return $this->status; }
    public function setStatus(SubscriptionStatus $status): self { $this->status = $status; return $this; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): self { $this->createdAt = $createdAt; return $this; }
    public function getUpdatedAt(): \DateTimeImmutable { return $this->updatedAt; }
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self { $this->updatedAt = $updatedAt; return $this; }
}
