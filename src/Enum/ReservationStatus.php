<?php

namespace App\Enum;

enum ReservationStatus: string
{
    case PENDING = 'PENDING';
    case CONFIRMED = 'CONFIRMED';
    case CANCELLED = 'CANCELLED';
    case COMPLETED = 'COMPLETED';
    case REFUNDED = 'REFUNDED';
    case EXPIRED = 'EXPIRED';
    case IN_PROGRESS = 'IN_PROGRESS';
}
