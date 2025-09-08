<?php

namespace App\Enum;

enum EventType: string
{
    case WEDDING = 'WEDDING';
    case BIRTHDAY = 'BIRTHDAY';
    case BAPTISM = 'BAPTISM';
    case MEETING = 'MEETING';
    case TEAM_BUILDING = 'TEAM_BUILDING';
    case CONFERENCE = 'CONFERENCE';
    case FESTIVAL = 'FESTIVAL';
    case CONCERT = 'CONCERT';
    case CHARITY = 'CHARITY';
    case OTHER = 'OTHER';
}
