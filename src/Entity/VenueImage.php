<?php

namespace App\Entity;

use App\Repository\VenueImageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VenueImageRepository::class)]
#[ORM\Table(name: "venue_image")]
class VenueImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $fileUrl;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isMain = false;

    #[ORM\ManyToOne(targetEntity: Venue::class, inversedBy: 'images')]
    #[ORM\JoinColumn(name: 'venue_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Venue $venue;

    public function getId(): ?int { return $this->id; }
    public function getFileUrl(): string { return $this->fileUrl; }
    public function setFileUrl(string $fileUrl): self { $this->fileUrl = $fileUrl; return $this; }
    public function isMain(): bool { return $this->isMain; }
    public function setIsMain(bool $isMain): self { $this->isMain = $isMain; return $this; }
    public function getVenue(): Venue { return $this->venue; }
    public function setVenue(Venue $venue): self { $this->venue = $venue; return $this; }
}
