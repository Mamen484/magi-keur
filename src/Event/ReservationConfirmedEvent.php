<?php

namespace App\Event;

use App\Entity\Reservation;
use Symfony\Contracts\EventDispatcher\Event;

class ReservationConfirmedEvent extends Event
{
    public function __construct(public readonly Reservation $reservation) {}
}
