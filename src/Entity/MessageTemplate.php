<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "message_template")]
class MessageTemplate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length:120)]
    private ?string $name = null;

    #[ORM\Column(type: Types::STRING, length:20)]
    private ?string $channel = null; // email, sms

    #[ORM\Column(type: Types::STRING, length:200, nullable:true)]
    private ?string $subject = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $body = null;

    #[ORM\Column(type: Types::STRING, length:10, options:["default" => "fr_FR"])]
    private ?string $locale = 'fr_FR';

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(?string $name): self { $this->name = $name; return $this; }
    public function getChannel(): ?string { return $this->channel; }
    public function setChannel(?string $channel): self { $this->channel = $channel; return $this; }
    public function getSubject(): ?string { return $this->subject; }
    public function setSubject(?string $subject): self { $this->subject = $subject; return $this; }
    public function getBody(): ?string { return $this->body; }
    public function setBody(?string $body): self { $this->body = $body; return $this; }
    public function getLocale(): ?string { return $this->locale; }
    public function setLocale(?string $locale): self { $this->locale = $locale; return $this; }
}
