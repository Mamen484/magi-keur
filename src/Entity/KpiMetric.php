<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\KpiMetricRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: KpiMetricRepository::class)]
#[ORM\Table(name: "kpi_metric")]
#[ApiResource(
    normalizationContext: ['groups' => ['kpi:read']],
    denormalizationContext: ['groups' => ['kpi:write']]
)]
class KpiMetric
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(["kpi:read"])]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 120)]
    #[Groups(["kpi:read","kpi:write"])]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision:12, scale:2)]
    #[Groups(["kpi:read","kpi:write"])]
    private string $value;

    #[ORM\Column(type: Types::STRING, length: 20, nullable: true)]
    #[Groups(["kpi:read","kpi:write"])]
    private ?string $unit = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, nullable: true)]
    #[Groups(["kpi:read","kpi:write"])]
    private ?\DateTimeImmutable $periodStart = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, nullable: true)]
    #[Groups(["kpi:read","kpi:write"])]
    private ?\DateTimeImmutable $periodEnd = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name:"user_id", referencedColumnName:"id", nullable:false, onDelete:"CASCADE")]
    #[Groups(["kpi:read","kpi:write"])]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Company::class)]
    #[ORM\JoinColumn(name:"company_id", referencedColumnName:"id", onDelete:"SET NULL")]
    #[Groups(["kpi:read","kpi:write"])]
    private ?Company $company = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    #[Groups(["kpi:read","kpi:write"])]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(?string $name): self { $this->name = $name; return $this; }
    public function getValue(): string { return $this->value; }
    public function setValue(string $value): self { $this->value = $value; return $this; }
    public function getUnit(): ?string { return $this->unit; }
    public function setUnit(?string $unit): self { $this->unit = $unit; return $this; }
    public function getPeriodStart(): ?\DateTimeImmutable { return $this->periodStart; }
    public function setPeriodStart(?\DateTimeImmutable $periodStart): self { $this->periodStart = $periodStart; return $this; }
    public function getPeriodEnd(): ?\DateTimeImmutable { return $this->periodEnd; }
    public function setPeriodEnd(?\DateTimeImmutable $periodEnd): self { $this->periodEnd = $periodEnd; return $this; }
    public function getUser(): ?User { return $this->user; }
    public function setUser(?User $user): self { $this->user = $user; return $this; }
    public function getCompany(): ?Company { return $this->company; }
    public function setCompany(?Company $company): self { $this->company = $company; return $this; }
    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): self { $this->createdAt = $createdAt; return $this; }
}
