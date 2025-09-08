<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "activity_category")]
class ActivityCategory
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Activity::class, inversedBy: "activityCategories")]
    #[ORM\JoinColumn(name: "activity_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private ?Activity $activity = null;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(name: "category_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private ?Category $category = null;

    public function getActivity(): ?Activity { return $this->activity; }
    public function setActivity(?Activity $activity): self { $this->activity = $activity; return $this; }
    public function getCategory(): ?Category { return $this->category; }
    public function setCategory(?Category $category): self { $this->category = $category; return $this; }
}
