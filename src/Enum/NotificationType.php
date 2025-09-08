<?php

namespace App\Enum;

enum NotificationType: string
{
    case EMAIL = 'EMAIL';
    case SMS = 'SMS';
}
