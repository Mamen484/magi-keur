<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "property_category")]
class PropertyCategory
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Property::class, inversedBy: "propertyCategories")]
    #[ORM\JoinColumn(name: "property_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private ?Property $property = null;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(name: "category_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private ?Category $category = null;

    public function getProperty(): ?Property { return $this->property; }
    public function setProperty(?Property $property): self { $this->property = $property; return $this; }
    public function getCategory(): ?Category { return $this->category; }
    public function setCategory(?Category $category): self { $this->category = $category; return $this; }
}
