<?php

namespace App\Repository;

use App\Entity\Reservation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    /**
     * Vérifie si l’utilisateur a bien réservé cette réservation (et qu’elle est terminée).
     */
    public function userHasReservedReservation(User $user, Reservation $reservation): bool
    {
        return (bool) $this->createQueryBuilder('res')
            ->select('COUNT(res.id)')
            ->andWhere('res.id = :reservationId')
            ->andWhere('res.user = :user')
            ->andWhere('res.endDate < :now') // On suppose que `endDate` existe
            ->setParameter('reservationId', $reservation->getId())
            ->setParameter('user', $user)
            ->setParameter('now', new \DateTimeImmutable())
            ->getQuery()
            ->getSingleScalarResult();
    }
}
