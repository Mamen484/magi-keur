<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "supplier_category")]
class SupplierCategory
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Supplier::class, inversedBy: "supplierCategories")]
    #[ORM\JoinColumn(name: "supplier_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private ?Supplier $supplier = null;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(name: "category_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private ?Category $category = null;

    public function getSupplier(): ?Supplier { return $this->supplier; }
    public function setSupplier(?Supplier $supplier): self { $this->supplier = $supplier; return $this; }
    public function getCategory(): ?Category { return $this->category; }
    public function setCategory(?Category $category): self { $this->category = $category; return $this; }
}
