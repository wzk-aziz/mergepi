<?php

namespace App\Repository;

use App\Entity\Annonces;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Annonces>
 *
 * @method Annonces|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonces|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonces[]    findAll()
 * @method Annonces[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnoncesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annonces::class);
    }


     
    public function paginationQuery ()
    {
        return $this->createQueryBuilder('a')
            
            ->orderBy('a.id', 'ASC')
    
            ->getQuery()
        ;
  }

//    public function findOneBySomeField($value): ?Annonces
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function searchBytitre(string $query): array
{
    return $this->createQueryBuilder('t')
        ->andWhere('t.titre LIKE :query')
        ->setParameter('query', '%' . $query . '%')
        ->getQuery()
        ->getResult();
}
public function searchByDescription(string $query): array
{
    return $this->createQueryBuilder('t')
        ->andWhere('t.description LIKE :query')
        ->setParameter('query', '%' . $query . '%')
        ->getQuery()
        ->getResult();
}
public function Trieparannonce(): array
{
    return $this->createQueryBuilder('c')
        ->orderBy('c.name', 'ASC')
        ->getQuery()
        ->getResult()
    ;
}
public function tire($searchQuery,$sort)
    {
        $query = $this->createQueryBuilder('p')
        ->andWhere('p.titre LIKE :searchQuery')
            ->setParameter('searchQuery', '%' . $searchQuery . '%');
            if ($sort === 'asc') {
                $query->orderBy('p.tire', 'ASC');
            } elseif ($sort === 'desc') {
            $query->orderBy('p.tire', 'DESC');
            }
            return $query->getQuery()->getResult();

    }
}