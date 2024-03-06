<?php

namespace App\Repository;

use App\Entity\Commnet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commnet>
 *
 * @method Commnet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commnet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commnet[]    findAll()
 * @method Commnet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommnetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commnet::class);
    }

//    /**
//     * @return Commnet[] Returns an array of Commnet objects
//     */
public function paginationQuery ()
{
    return $this->createQueryBuilder('a')
        
        ->orderBy('a.id', 'ASC')

        ->getQuery()
    ;
}

//    public function findOneBySomeField($value): ?Commnet
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
