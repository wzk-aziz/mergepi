<?php

namespace App\Repository;

use App\Entity\Inventory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Inventory>
 */
class InventoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inventory::class);
    }

    /**
     * Search for inventory items based on a query string.
     *
     * @param string $query The search query
     * @return Inventory[] The array of matching Inventory objects
     */
    //json search function by title 
    public function search(string $query): array
    {
        return $this->createQueryBuilder('i')
            ->where('i.title LIKE :query OR i.description LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->getQuery()
            ->getResult();
    }
}
