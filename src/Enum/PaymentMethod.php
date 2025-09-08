<?php

namespace App\Enum;

enum PaymentMethod: string
{
    case CARD = 'CARD';
    case CASH = 'CASH';
    case BANK_TRANSFER = 'BANK_TRANSFER';
    case PAYPAL = 'PAYPAL';
    case CRYPTOCURRENCY = 'CRYPTOCURRENCY';
    case CHEQUE = 'CHEQUE';
    case WALLET = 'WALLET';
    case GIFT_CARD = 'GIFT_CARD';
    case VOUCHER = 'VOUCHER';
    case SUBSCRIPTION = 'SUBSCRIPTION';
    case PREPAID_CARD = 'PREPAID_CARD';
    case OTHER = 'OTHER';
}
