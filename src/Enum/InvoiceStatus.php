<?php

namespace App\Enum;

enum InvoiceStatus: string
{
    case PENDING = 'PENDING';
    case COMPLETED = 'COMPLETED';
    case OVERDUE = 'OVERDUE';
    case SENT = 'SENT';
    case CANCELLED = 'CANCELLED';
    case EXPIRED = 'EXPIRED';
    case DRAFT = 'DRAFT';
    case PAID = 'PAID';
}
