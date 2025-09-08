<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "notification")]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name:"user_id", referencedColumnName:"id", onDelete:"SET NULL")]
    private ?User $user = null;

    #[ORM\Column(type: Types::STRING, length:20)]
    private ?string $channel = null; // email, sms

    #[ORM\ManyToOne(targetEntity: MessageTemplate::class)]
    #[ORM\JoinColumn(name:"template_id", referencedColumnName:"id", onDelete:"SET NULL")]
    private ?MessageTemplate $template = null;

    /**
     * @var string[]
     */
    #[ORM\Column(type: Types::JSON, nullable:true)]
    private ?array $payload = null;

    #[ORM\Column(type: Types::STRING, length:20, options:["default" => "queued"])]
    private ?string $status = 'queued';

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, nullable:true)]
    private ?\DateTimeImmutable $sentAt = null;

    public function getId(): ?int { return $this->id; }
    public function getUser(): ?User { return $this->user; }
    public function setUser(?User $user): self { $this->user = $user; return $this; }
    public function getChannel(): ?string { return $this->channel; }
    public function setChannel(?string $channel): self { $this->channel = $channel; return $this; }
    public function getTemplate(): ?MessageTemplate { return $this->template; }
    public function setTemplate(?MessageTemplate $template): self { $this->template = $template; return $this; }
    public function getPayload(): ?array { return $this->payload; }
    public function setPayload(?array $payload): self { $this->payload = $payload; return $this; }
    public function getStatus(): ?string { return $this->status; }
    public function setStatus(?string $status): self { $this->status = $status; return $this; }
    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): self { $this->createdAt = $createdAt; return $this; }
    public function getSentAt(): ?\DateTimeImmutable { return $this->sentAt; }
    public function setSentAt(?\DateTimeImmutable $sentAt): self { $this->sentAt = $sentAt; return $this; }
}
