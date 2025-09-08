<?php

namespace App\Enum;

enum DocumentType: string
{
    case LEASE = 'LEASE';
    case INVOICE = 'INVOICE';
    case ATTESTATION = 'ATTESTATION';
    case LETTER = 'LETTER';
}
