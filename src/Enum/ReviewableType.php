<?php

namespace App\Enum;

enum ReviewableType: string
{
    case PROPERTY = 'PROPERTY';
    case VENUE = 'VENUE';
    case EVENT = 'EVENT';
    case SUPPLIER = 'SUPPLIER';
    case ACTIVITY = 'ACTIVITY';

    public static function all(): array
    {
        return [
            self::PROPERTY,
            self::VENUE,
            self::EVENT,
            self::SUPPLIER,
            self::ACTIVITY,
        ];
    }
}
