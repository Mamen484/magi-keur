<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SubscriptionPlanRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SubscriptionPlanRepository::class)]
#[ORM\Table(name: "subscription_plan")]
#[ApiResource(
    normalizationContext: ['groups' => ['subscriptionplan:read']],
    denormalizationContext: ['groups' => ['subscriptionplan:write']]
)]
class SubscriptionPlan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(['subscriptionplan:read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 50)]
    #[Groups(['subscriptionplan:read', 'subscriptionplan:write'])]
    private string $name;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2)]
    #[Groups(['subscriptionplan:read', 'subscriptionplan:write'])]
    private string $price;

    #[ORM\Column(type: Types::STRING, length: 3, options: ["default" => "EUR"])]
    #[Groups(['subscriptionplan:read', 'subscriptionplan:write'])]
    private string $currency = 'EUR';

    /**
     * @var string[]
     */
    #[ORM\Column(type: Types::JSON)]
    #[Groups(['subscriptionplan:read', 'subscriptionplan:write'])]
    private array $features = [];

    #[ORM\Column(type: Types::STRING, length: 20, options: ["default" => "month"])]
    #[Groups(['subscriptionplan:read', 'subscriptionplan:write'])]
    private string $interval = 'month';

    #[ORM\Column(type: Types::BOOLEAN, options: ["default" => true])]
    #[Groups(['subscriptionplan:read', 'subscriptionplan:write'])]
    private bool $isActive = true;

    public function __construct(string $name, string $price, array $features, string $currency = 'EUR', string $interval = 'month', bool $isActive = true)
    {
        $this->name = $name;
        $this->price = $price;
        $this->currency = $currency;
        $this->features = $features;
        $this->interval = $interval;
        $this->isActive = $isActive;
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }
    public function getPrice(): string { return $this->price; }
    public function setPrice(string $price): self { $this->price = $price; return $this; }
    public function getCurrency(): string { return $this->currency; }
    public function setCurrency(string $currency): self { $this->currency = $currency; return $this; }
    public function getFeatures(): array { return $this->features; }
    public function setFeatures(array $features): self { $this->features = $features; return $this; }
    public function getInterval(): string { return $this->interval; }
    public function setInterval(string $interval): self { $this->interval = $interval; return $this; }
    public function isActive(): bool { return $this->isActive; }
    public function setIsActive(bool $isActive): self { $this->isActive = $isActive; return $this; }
}
