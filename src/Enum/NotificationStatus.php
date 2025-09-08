<?php

namespace App\Enum;

enum NotificationStatus: string
{
    case PENDING = 'PENDING';
    case SENT = 'SENT';
    case FAILED = 'FAILED';
}
