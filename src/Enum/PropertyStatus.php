<?php

namespace App\Enum;

enum PropertyStatus: string
{
    case DISPONIBLE = 'DISPONIBLE';
    case LOUE = 'LOUÉ';
    case VENDU = 'VENDU';
    case RESERVE = 'RÉSERVÉ';
    case EN_CONSTRUCTION = 'EN CONSTRUCTION';
    case EN_RENOVATION = 'EN RÉNOVATION';
    case A_LOUER = 'À LOUER';
    case A_VENDRE = 'À VENDRE';
}
