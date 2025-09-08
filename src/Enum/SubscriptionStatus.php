<?php

namespace App\Enum;

enum SubscriptionStatus: string
{
    case PENDING = 'PENDING';
    case ACTIVE = 'ACTIVE';
    case CANCELED = 'CANCELED';
    case EXPIRED = 'EXPIRED';
    case SUSPENDED = 'SUSPENDED';
    case COMPLETED = 'COMPLETED';
    case FAILED = 'FAILED';
    case REFUNDED = 'REFUNDED';
    case PAUSED = 'PAUSED';
    case INACTIVE = 'INACTIVE';
    case RENEWED = 'RENEWED';
}
