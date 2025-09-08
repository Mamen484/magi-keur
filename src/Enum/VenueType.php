<?php

namespace App\Enum;

enum VenueType: string
{
    case ROOM = 'ROOM';
    case HALL = 'HALL';
    case OUTDOOR = 'OUTDOOR';
    case OTHER = 'OTHER';

    public static function all(): array
    {
        return [
            self::ROOM,
            self::HALL,
            self::OUTDOOR,
            self::OTHER,
        ];
    }
}
