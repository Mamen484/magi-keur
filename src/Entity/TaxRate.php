<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "tax_rate")]
class TaxRate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 120)]
    private string $name;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private string $ratePercent;

    #[ORM\Column(type: Types::STRING, length: 2, nullable: true)]
    private ?string $countryCode = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private \DateTimeImmutable $createdAt;

    public function __construct(string $name, string $ratePercent, ?string $countryCode = null)
    {
        $this->name = $name;
        $this->ratePercent = $ratePercent;
        $this->countryCode = $countryCode;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }
    public function getRatePercent(): string { return $this->ratePercent; }
    public function setRatePercent(string $ratePercent): self { $this->ratePercent = $ratePercent; return $this; }
    public function getCountryCode(): ?string { return $this->countryCode; }
    public function setCountryCode(?string $countryCode): self { $this->countryCode = $countryCode; return $this; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): self { $this->createdAt = $createdAt; return $this; }
}
