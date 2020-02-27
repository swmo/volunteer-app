<?php

namespace App\Repository;
use App\Entity\Organisation;
use App\Entity\Person;
use App\Manager\UserOrganisationManager;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Person|null find($id, $lockMode = null, $lockVersion = null)
 * @method Person|null findOneBy(array $criteria, array $orderBy = null)
 * @method Person[]    findAll()
 * @method Person[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRepository extends ServiceEntityRepository
{
 

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Person::class);
   
    }

    public function findByOrganisation($organisation){
        return $this->createQueryBuilder('p')
            ->leftJoin('p.organisations', 'o')
            ->andWhere('o = :organisation')
            ->setParameter('organisation', $organisation)
            ->addOrderBy('p.lastname', 'ASC')
            ->addOrderBy('p.firstname', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByOrganisationFirstnameEmail(Organisation $organisation, string $firstname, string $email){
        
        return $this->createQueryBuilder('p')
            ->andWhere('LOWER(p.firstname) = :firstname')
            ->andWhere('LOWER(p.email) = :email')
            ->leftJoin('p.organisations', 'o')
            ->andWhere('o = :organisation')
            ->setParameter('firstname', strtolower($firstname))
            ->setParameter('email', strtolower($email))
            ->setParameter('organisation', ($organisation))
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Person[] Returns an array of Person objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Person
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
