<?php

namespace App\Repository;

use App\Entity\Huuu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Huuu|null find($id, $lockMode = null, $lockVersion = null)
 * @method Huuu|null findOneBy(array $criteria, array $orderBy = null)
 * @method Huuu[]    findAll()
 * @method Huuu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HuuuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Huuu::class);
    }

    // /**
    //  * @return Huuu[] Returns an array of Huuu objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Huuu
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
