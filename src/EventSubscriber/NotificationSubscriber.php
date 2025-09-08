<?php

namespace App\EventSubscriber;

use App\Entity\Notification;
use App\Enum\NotificationStatus;
use App\Enum\NotificationType;
use App\Event\ReservationConfirmedEvent;
use App\Message\NotificationMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class NotificationSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private MessageBusInterface $bus
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            ReservationConfirmedEvent::class => 'onReservationConfirmed',
        ];
    }

    public function onReservationConfirmed(ReservationConfirmedEvent $event): void
    {
        $reservation = $event->reservation;
        $user = $reservation->getUser();

        $notification = new Notification();
        $notification->setUser($user);
        $notification->setType(NotificationType::EMAIL);
        $notification->setContent("Votre réservation du {$reservation->getStartDate()->format('d/m/Y')} a été confirmée !");
        $notification->setStatus(NotificationStatus::PENDING);
        $notification->setScheduledAt(new \DateTimeImmutable('+10 seconds'));

        $this->em->persist($notification);
        $this->em->flush();

        // Envoie dans la file
        $this->bus->dispatch(new NotificationMessage($notification->getId()));
    }
}
