<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\Table(name: "feature_flag")]
class FeatureFlag
{
    #[ORM\Id]
    #[ORM\Column(type: "string", length: 80)]
    #[Groups(["feature:read"])]
    private ?string $key = null;

    #[ORM\Column(type: "text", nullable: true)]
    #[Groups(["feature:read"])]
    private ?string $description = null;

    public function getKey(): ?string { return $this->key; }
    public function setKey(?string $key): self { $this->key = $key; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): self { $this->description = $description; return $this; }
}
