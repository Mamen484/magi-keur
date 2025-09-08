<?php

namespace App\Enum;

enum EventStatus: string
{
    case DRAFT = 'DRAFT';
    case PUBLISHED = 'PUBLISHED';
    case BOOKING_OPEN = 'BOOKING_OPEN';
    case BOOKING_CLOSED = 'BOOKING_CLOSED';
    case ONGOING = 'ONGOING';
    case CANCELLED = 'CANCELLED';
    case POSTPONED = 'POSTPONED';
    case FINISHED = 'FINISHED';
}
