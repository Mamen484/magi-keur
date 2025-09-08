<?php

namespace App\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class PolygonType extends Type
{
    public const NAME = 'polygon';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'POLYGON';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        return $value;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
