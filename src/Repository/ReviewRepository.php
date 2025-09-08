<?php

namespace App\Repository;

use App\Entity\Reservation;
use App\Entity\Review;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Review>
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    /**
     * Vérifie si un utilisateur a déjà laissé un avis pour une réservation donnée.
     */
    public function hasUserReviewedReservation(User $user, Reservation $reservation): bool
    {
        return (bool) $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->andWhere('r.reviewer = :user')
            ->andWhere('r.reservation = :reservation')
            ->setParameter('user', $user)
            ->setParameter('reservation', $reservation)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Retourne la note moyenne donnée à un pro via les réservations.
     */
    public function getAverageRatingForProfessional(Reservation $reservation): float
    {
        return (float) $this->createQueryBuilder('r')
            ->select('AVG(r.rating)')
            ->andWhere('r.reservation = :reservation')
            ->setParameter('reservation', $reservation)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
