<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class AuditLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length:120)]
    private string $tableName;

    #[ORM\Column(type: Types::STRING, length:120)]
    private string $recordPk;

    #[ORM\Column(type: Types::STRING, length:20)]
    private string $action;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name:"user_id", referencedColumnName:"id", nullable:true, onDelete:"SET NULL")]
    private ?User $user = null;

    /**
     * @var string[]
     */
    #[ORM\Column(type: Types::JSON, nullable:true)]
    private ?array $diff = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private \DateTimeImmutable $createdAt;

    public function __construct() { $this->createdAt = new \DateTimeImmutable(); }

    public function getId(): ?int { return $this->id; }
    public function getTableName(): string { return $this->tableName; }
    public function setTableName(string $tableName): self { $this->tableName = $tableName; return $this; }
    public function getRecordPk(): string { return $this->recordPk; }
    public function setRecordPk(string $recordPk): self { $this->recordPk = $recordPk; return $this; }
    public function getAction(): string { return $this->action; }
    public function setAction(string $action): self { $this->action = $action; return $this; }
    public function getUser(): ?User { return $this->user; }
    public function setUser(?User $user): self { $this->user = $user; return $this; }
    public function getDiff(): ?array { return $this->diff; }
    public function setDiff(?array $diff): self { $this->diff = $diff; return $this; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): self { $this->createdAt = $createdAt; return $this; }
}
