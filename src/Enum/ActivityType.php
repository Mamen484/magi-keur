<?php

namespace App\Enum;

enum ActivityType: string
{
    case EXCURSION = 'EXCURSION';
    case RESTAURANT = 'RESTAURANT';
    case SHOW = 'SHOW';
    case SPORT = 'SPORT';
    case LEISURE = 'LEISURE';
    case CULTURE = 'CULTURE';
    case WELLNESS = 'WELLNESS';
    case GASTRONOMY = 'GASTRONOMY';
    case TOURISM = 'TOURISM';
    case OTHER = 'OTHER';
    // Types selon les besoins : 'Excursion', 'Restaurant', 'Spectacle', 'Sport', 'Loisirs', 'Culture', 'Bien-être', 'Gastronomie', 'Tourisme'.
}
