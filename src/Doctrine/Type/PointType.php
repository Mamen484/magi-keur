<?php

namespace App\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class PointType extends Type
{
    public const NAME = 'point';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        // PostGIS column declaration
        return 'POINT';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        // Ici tu peux parser en objet (ex: GeoJSON), pour l’instant on garde le WKT tel quel
        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        // Si tu passes un WKT/GeoJSON → tu peux adapter ici
        return $value;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
