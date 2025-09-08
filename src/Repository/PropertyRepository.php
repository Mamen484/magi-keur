<?php

namespace App\Repository;

use App\Entity\Property;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Property>
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Property::class);
    }

    /**
     * Recherche des propriétés avec filtres
     */
    public function searchProperties(?string $city, ?string $type, ?float $maxPrice, ?\DateTimeInterface $startDate, ?\DateTimeInterface $endDate, int $limit = 20, int $offset = 0): array
    {
        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.isVisible = :visible')
            ->setParameter('visible', true);

        if ($city) {
            $qb->andWhere('p.city LIKE :city')
               ->setParameter('city', "%$city%");
        }

        if ($type) {
            $qb->andWhere('p.type = :type')
               ->setParameter('type', $type);
        }

        if ($maxPrice) {
            $qb->andWhere('p.price <= :maxPrice')
               ->setParameter('maxPrice', $maxPrice);
        }

        // Filtrage par disponibilité
        if ($startDate && $endDate) {
            $qb->join('p.availabilities', 'a')
               ->andWhere('a.date BETWEEN :start AND :end')
               ->andWhere('a.isBooked = false')
               ->setParameter('start', $startDate->format('Y-m-d'))
               ->setParameter('end', $endDate->format('Y-m-d'));
        }

        return $qb
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findPopularProperties(int $limit = 5): array
    {
        return $this->createQueryBuilder('p')
            ->select('p as property', 'COUNT(r.id) as reservationsCount', 'pi as mainImage')
            ->leftJoin('p.reservations', 'r')
            ->leftJoin('p.propertyImages', 'pi', 'WITH', 'pi.isMain = true')
            ->where('p.isVisible = true')
            ->groupBy('p.id')
            ->addGroupBy('pi.id')
            ->orderBy('reservationsCount', 'DESC')
            ->addOrderBy('p.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne les $limit propriétés ayant le plus de réservations
     *
     * @return array<int, array{property: Property, reservationsCount: int}>
     */
    public function findTopByReservations(int $limit = 10): array
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p', 'COUNT(r.id) AS reservationsCount')
            ->leftJoin('p.reservations', 'r')
            ->groupBy('p.id')
            ->orderBy('COUNT(r.id)', 'DESC')
            ->setMaxResults($limit);

        $rows = $qb->getQuery()->getResult();

        $results = [];
        foreach ($rows as $row) {
            if (is_array($row)) {
                $property = $row[0] ?? null;
                $count = isset($row['reservationsCount']) ? (int)$row['reservationsCount'] : (int)($row[1] ?? 0);
            } else {
                $property = $row;
                $count = method_exists($property, 'getReservationsCount') ? $property->getReservationsCount() : 0;
            }

            if ($property instanceof Property) {
                $results[] = [
                    'property' => $property,
                    'reservationsCount' => $count,
                ];
            }
        }

        return $results;
    }
}
