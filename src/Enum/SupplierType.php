<?php

namespace App\Enum;

enum SupplierType: string
{
    case PLUMBER = 'PLUMBER';
    case ELECTRICIAN = 'ELECTRICIAN';
    case CARPENTER = 'CARPENTER';
    case PAINTER = 'PAINTER';
    case MECHANIC = 'MECHANIC';
    case CLEANING = 'CLEANING';
    case GARDENER = 'GARDENER';
    case CATERER = 'CATERER';
    case GUIDE = 'GUIDE';
    case PHOTOGRAPHER = 'PHOTOGRAPHER';
    case DJ = 'DJ';
    case SECURITY = 'SECURITY';
    case TRANSPORT = 'TRANSPORT';
    case OTHER = 'OTHER';

    public static function all(): array
    {
        return [
            self::PLUMBER,
            self::ELECTRICIAN,
            self::CARPENTER,
            self::PAINTER,
            self::MECHANIC,
            self::CLEANING,
            self::GARDENER,
            self::CATERER,
            self::GUIDE,
            self::PHOTOGRAPHER,
            self::DJ,
            self::SECURITY,
            self::TRANSPORT,
            self::OTHER,
        ];
    }
}
