<?php

namespace App\Entity;

use App\Enum\PaymentMethod;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "payment_provider")]
class PaymentProvider
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 80)]
    private string $name;

    #[ORM\Column(type: "string", length: 50)]
    private string $type;

    #[ORM\Column(type: Types::BOOLEAN, options: ["default" => true])]
    private bool $isActive = true;

    /**
     * @var string[]
     */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $config = null;

    public function __construct(string $name, string $type, bool $isActive = true, ?array $config = null)
    {
        $this->name = $name;
        $this->type = $type;
        $this->isActive = $isActive;
        $this->config = $config;
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }
    public function getType(): string { return $this->type; }
    public function setType(string $type): self { $this->type = $type; return $this; }
    public function isActive(): bool { return $this->isActive; }
    public function setIsActive(bool $isActive): self { $this->isActive = $isActive; return $this; }
    public function getConfig(): ?array { return $this->config; }
    public function setConfig(?array $config): self { $this->config = $config; return $this; }
}
