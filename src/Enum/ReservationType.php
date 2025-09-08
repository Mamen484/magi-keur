<?php

namespace App\Enum;

enum ReservationType: string
{
    case PROPERTY = 'PROPERTY';
    case VENUE = 'VENUE';
    case EQUIPMENT = 'EQUIPMENT';
    case ACTIVITY = 'ACTIVITY';
    case SUPPLIER_SERVICE = 'SUPPLIER_SERVICE';

    public static function all(): array
    {
        return [
            self::PROPERTY,
            self::VENUE,
            self::EQUIPMENT,
            self::ACTIVITY,
            self::SUPPLIER_SERVICE,
        ];
    }
}
