<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Enum\InvitationStatus;
use App\Repository\GuestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GuestRepository::class)]
#[ORM\Table(name: "guest")]
#[ApiResource(
    normalizationContext: ['groups' => ['guest:read']],
    denormalizationContext: ['groups' => ['guest:write']]
)]
class Guest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(['guest:read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length:100)]
    private ?string $name = null;

    #[ORM\Column(type: Types::STRING, length: 120, nullable: true)]
    #[Assert\Email]
    #[Groups(['guest:read', 'guest:write'])]
    private ?string $email = null;

    #[ORM\Column(type: Types::STRING, length: 30, nullable: true)]
    #[Groups(['guest:read', 'guest:write'])]
    private ?string $phone = null;

    #[ORM\Column(enumType: InvitationStatus::class)]
    #[Groups(['guest:read', 'guest:write'])]
    private InvitationStatus $invitationStatus;

    #[ORM\ManyToOne(inversedBy: "guests")]
    #[ORM\JoinColumn(onDelete: "CASCADE", nullable: true)]
    #[Groups(['guest:read', 'guest:write'])]
    private ?Event $event = null;

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }
    public function getEmail(): ?string { return $this->email; }
    public function setEmail(?string $email): self { $this->email = $email; return $this; }
    public function getPhone(): ?string { return $this->phone; }
    public function setPhone(?string $phone): self { $this->phone = $phone; return $this; }
    public function getEvent(): ?Event { return $this->event; }
    public function setEvent(?Event $event): self { $this->event = $event; return $this; }
    public function getInvitationStatus(): InvitationStatus { return $this->invitationStatus; }
    public function setInvitationStatus(InvitationStatus $invitationStatus): self { $this->invitationStatus = $invitationStatus; return $this; }
}
