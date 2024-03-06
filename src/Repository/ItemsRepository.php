<?php

namespace App\Repository;

use App\Entity\Items;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;



/**
 * @extends ServiceEntityRepository<Items>
 *
 * @method Items|null find($id, $lockMode = null, $lockVersion = null)
 * @method Items|null findOneBy(array $criteria, array $orderBy = null)
 * @method Items[]    findAll()
 * @method Items[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Items::class);
    }

    public function findAllPaginated($page = 1, $limit = 10)
    {
        $itemsQuery = $this->createQueryBuilder('i')
            ->getQuery()
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        return new Paginator($itemsQuery);
    }

    public function findBySearchTerm($searchTerm)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.name LIKE :searchTerm OR i.description LIKE :searchTerm OR i.ref LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->getQuery()
            ->getResult();
    }
}
