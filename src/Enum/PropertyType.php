<?php

namespace App\Enum;

enum PropertyType: string
{
    case MAISON = 'MAISON';
    case APPARTEMENT = 'APPARTEMENT';
    case TERRAIN = 'TERRAIN';
    case LOCAL_COMMERCIAL = 'LOCAL_COMMERCIAL';
    case GARAGE = 'GARAGE';
    case PARKING = 'PARKING';
    case AUTRE = 'AUTRE';
    // Ajouter d'autres types selon ton besoin
}
