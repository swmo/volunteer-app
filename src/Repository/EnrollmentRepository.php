<?php

namespace App\Repository;

use App\Entity\Enrollment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\Mission;
use App\Entity\Organisation;
use App\Entity\Person;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Enrollment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Enrollment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Enrollment[]    findAll()
 * @method Enrollment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnrollmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Enrollment::class);
    }


    public function findByMission(Mission $mission)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.missionChoice01 = :mission or e.missionChoice02 = :mission ')
            ->setParameter('mission', $mission->getId())
            ->orderBy('e.email', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    public function findByOrganisationAndPerson(Organisation $organisation, Person $person){
        return $this->createQueryBuilder('e')
        ->leftJoin('e.project', 'p')
        ->leftJoin('p.organisation', 'o')
        ->andWhere('o = :organisation')
        ->andWhere('LOWER(e.firstname) = :firstname')
        ->andWhere('LOWER(e.email) = :email')
        ->setParameter('firstname', strtolower($person->getFirstname()))
        ->setParameter('email', strtolower($person->getEmail()))
        ->setParameter('organisation', $organisation)
        ->getQuery()
        ->getResult();
    }

    // /**
    //  * @return Enrollment[] Returns an array of Enrollment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Enrollment
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
