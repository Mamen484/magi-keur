<?php

namespace App\Entity;

use App\Repository\ActivityImageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityImageRepository::class)]
#[ORM\Table(name: "activity_image")]
class ActivityImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $fileUrl = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $isMain = false;

    #[ORM\ManyToOne(inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Activity $activity = null;

    public function getId(): ?int { return $this->id; }
    public function getFileUrl(): ?string { return $this->fileUrl; }
    public function setFileUrl(?string $fileUrl): self { $this->fileUrl = $fileUrl; return $this; }
    public function isMain(): ?bool { return $this->isMain; }
    public function setIsMain(bool $isMain): self { $this->isMain = $isMain; return $this; }
    public function getActivity(): ?Activity { return $this->activity; }
    public function setActivity(?Activity $activity): self { $this->activity = $activity; return $this; }
}
