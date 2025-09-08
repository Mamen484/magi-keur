<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\AddressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Validator\Constraints as AppAssert;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
#[ORM\Table(name: "address")]
#[ApiResource(
    normalizationContext: ['groups' => ['address:read']],
    denormalizationContext: ['groups' => ['address:write']]
)]
class Address
{
    #[ORM\Id, ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(["address:read"])]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length:255)]
    #[Groups(["address:read", "address:write"])]
    private ?string $street = null;

    #[ORM\Column(type: Types::STRING, length:120)]
    #[Groups(["address:read", "address:write"])]
    private ?string $city = null;

    #[ORM\Column(type: Types::STRING, length:120, nullable:true)]
    #[Groups(["address:read", "address:write"])]
    #[AppAssert\PostalCodeByCountry]
    private ?string $postalCode = null;

    #[ORM\Column(type: Types::STRING, length:120)]
    #[Groups(["address:read", "address:write"])]
    private ?string $country = null;

    #[ORM\Column(type: Types::STRING, length: 2, nullable: true)]
    #[Groups(["address:read", "address:write"])]
    private ?string $countryCode = null;

    #[ORM\Column(type: Types::FLOAT, nullable:true)]
    #[Groups(["address:read", "address:write"])]
    private ?float $latitude = null;

    #[ORM\Column(type: Types::FLOAT, nullable:true)]
    #[Groups(["address:read", "address:write"])]
    private ?float $longitude = null;

    #[ORM\Column(type: "point", nullable: true)]
    private ?array $geom = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    #[Groups(["address:read"])]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    #[Groups(["address:read"])]
    private \DateTimeImmutable $updatedAt;

    #[ORM\OneToMany(mappedBy: 'address', targetEntity: Activity::class)]
    private Collection $activities;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->activities = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getStreet(): ?string { return $this->street; }
    public function setStreet(?string $street): self { $this->street = $street; return $this; }
    public function getCity(): ?string { return $this->city; }
    public function setCity(?string $city): self { $this->city = $city; return $this; }
    public function getPostalCode(): ?string { return $this->postalCode; }
    public function setPostalCode(?string $postalCode): self { $this->postalCode = $postalCode; return $this; }
    public function getCountry(): ?string { return $this->country; }
    public function setCountry(?string $country): self { $this->country = $country; return $this; }
    public function getCountryCode(): ?string { return $this->countryCode; }
    public function setCountryCode(?string $countryCode): self { $this->countryCode = $countryCode; return $this; }
    public function getLatitude(): ?float { return $this->latitude; }
    public function setLatitude(?float $latitude): self { $this->latitude = $latitude; return $this; }
    public function getLongitude(): ?float { return $this->longitude; }
    public function setLongitude(?float $longitude): self { $this->longitude = $longitude; return $this; }
    public function getGeom(): ?array { return $this->geom; }
    public function setGeom(?array $geom): self { $this->geom = $geom; return $this; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): self { $this->createdAt = $createdAt; return $this; }
    public function getUpdatedAt(): \DateTimeImmutable { return $this->updatedAt; }
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self { $this->updatedAt = $updatedAt; return $this; }

    /** @return Collection<int, Activity> */
    public function getActivities(): Collection { return $this->activities; }
    public function addActivity(Activity $activity): self { if (!$this->activities->contains($activity)) { $this->activities->add($activity); $activity->setAddress($this); } return $this; }
    public function removeActivity(Activity $activity): self { if ($this->activities->removeElement($activity)) { if ($activity->getAddress() === $this) { $activity->setAddress(null); }} return $this; }
}
