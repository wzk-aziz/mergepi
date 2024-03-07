<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

/**
     * Recherche les événements par place.
     *
     * @param string $place L'place à rechercher
     * @return Event[] Retourne un tableau d'événements correspondant à l'place
     */
   public function findByPlace(string $place): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.place LIKE :place')
            ->setParameter('place', '%'.$place.'%')
            ->getQuery()
            ->getResult();
}






public function searchByEventName(string $query): array
{
    return $this->createQueryBuilder('e')
        ->andWhere('e.eventName LIKE :query')
        ->setParameter('query', '%' . $query . '%')
        ->getQuery()
        ->getResult();
}
public function searchByDateD(string $query): array
{
    return $this->createQueryBuilder('t')
        ->andWhere('t.startDate LIKE :query')
        ->setParameter('query', '%' . $query . '%')
        ->getQuery()
        ->getResult();
}
public function searchByDateF(string $query): array
{
    return $this->createQueryBuilder('t')
        ->andWhere('t.endDate LIKE :query')
        ->setParameter('query', '%' . $query . '%')
        ->getQuery()
        ->getResult();
}
public function searchByPlace(string $query): array 
{

    return $this->createQueryBuilder('t')

    ->andWhere('t.place LIKE :query')
    ->setParameter('query', '%' . $query . '%')
    ->getQuery()
    ->getResult();
}



}
