<?php

namespace App\Enum;

enum InvitationStatus: string
{
    case PENDING = 'PENDING';
    case ACCEPTED = 'ACCEPTED';
    case DECLINED = 'DECLINED';
    case MAYBE = 'MAYBE';
    case CANCELLED = 'CANCELLED';
    case NO_RESPONSE = 'NO_RESPONSE';

    public static function all(): array
    {
        return [
            self::PENDING, // L'invitation a été envoyée, en attente de réponse
            self::ACCEPTED, // L'invité a accepté l'invitation / confirmé sa présence
            self::DECLINED, // L'invité a refusé l'invitation
            self::MAYBE, // L'invité a répondu « peut-être » / non confirmé
            self::CANCELLED, // L'invitation a été annulée par l'organisateur
            self::NO_RESPONSE, // Aucun retour reçu dans le délai imparti
        ];
    }
}
