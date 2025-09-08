<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\Table(name: "company_feature_flag")]
class CompanyFeatureFlag
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Company::class)]
    #[ORM\JoinColumn(name: "company_id", referencedColumnName: "id", onDelete: "CASCADE")]
    #[Groups(["feature:read", "feature:write"])]
    private ?Company $company = null;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: FeatureFlag::class)]
    #[ORM\JoinColumn(name: "flag_key", referencedColumnName: "key", onDelete: "CASCADE")]
    #[Groups(["feature:read", "feature:write"])]
    private ?FeatureFlag $flag = null;

    #[ORM\Column(type: "boolean", options: ["default" => true])]
    #[Groups(["feature:read", "feature:write"])]
    private bool $enabled = true;

    public function getCompany(): ?Company { return $this->company; }
    public function setCompany(?Company $company): self { $this->company = $company; return $this; }
    public function getFlag(): ?FeatureFlag { return $this->flag; }
    public function setFlag(?FeatureFlag $flag): self { $this->flag = $flag; return $this; }
    public function isEnabled(): bool { return $this->enabled; }
    public function setEnabled(bool $enabled): self { $this->enabled = $enabled; return $this; }
}
