<?php

namespace App\Enum;

enum PaymentStatus: string
{
    case PENDING = 'PENDING';
    case COMPLETED = 'COMPLETED';
    case FAILED = 'FAILED';
    case REFUNDED = 'REFUNDED';
    case CANCELLED = 'CANCELLED';
    case EXPIRED = 'EXPIRED';
    case PROCESSING = 'PROCESSING';
    case PAID = 'PAID';
}
