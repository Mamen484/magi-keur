<?php

namespace App\EventSubscriber;

use App\Entity\Review;
use App\Repository\ReviewRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;

#[AsEntityListener(event: Events::prePersist, method: 'onPrePersist', entity: Review::class)]
#[AsEntityListener(event: Events::postPersist, method: 'onPostPersist', entity: Review::class)]
class ReviewSubscriber
{
    public function __construct(
        private readonly ReviewRepository $reviewRepository,
        private readonly ReservationRepository $reservationRepository,
        private readonly EntityManagerInterface $em
    ) {}

    public function onPrePersist(Review $review, LifecycleEventArgs $args): void
    {
        $user = $review->getReviewer();
        $reservation = $review->getReservation();

        // 🔒 1. Un seul avis par user / pro
        if ($this->reviewRepository->hasUserReviewedReservation($user, $reservation)) {
            throw new \LogicException('Vous avez déjà laissé un avis pour ce professionnel.');
        }

        // ✅ 2. Autorisation : l'utilisateur doit avoir une réservation terminée avec ce pro
        $hasInteracted = $this->reservationRepository->userHasReservedReservation($user, $reservation);
        if (!$hasInteracted) {
            throw new \LogicException("Vous ne pouvez évaluer ce professionnel sans l'avoir réservé.");
        }
    }

    public function onPostPersist(Review $review, PostPersistEventArgs $args): void
    {
        $em = $args->getObjectManager();

        // Si c'est une activité notée
        if ($review->getActivity()) {
            $activity = $review->getActivity();

            $avg = $this->reviewRepository->createQueryBuilder('r')
                ->select('AVG(r.rating)')
                ->andWhere('r.activity = :activity')
                ->setParameter('activity', $activity)
                ->getQuery()
                ->getSingleScalarResult();

            $activity->setAverageRating((float) $avg);
            $em->persist($activity);
        }

        // Si c'est une réservation notée
        if ($review->getReservation()) {
            $reservation = $review->getReservation();

                $avg = $this->reviewRepository->createQueryBuilder('r')
                    ->select('AVG(r.rating)')
                    ->andWhere('r.reservation = :reservation')
                    ->setParameter('reservation', $reservation)
                    ->getQuery()
                    ->getSingleScalarResult();

                $reservation->setAverageRating((float) $avg);
                $em->persist($reservation);
        }

        $em->flush();
    }
}
