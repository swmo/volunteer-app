<?php

namespace App\Manager;

use App\Entity\Organisation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class UserOrganisationManager 
{
    protected $security = null;

    public function __construct(Security $security, EntityManagerInterface $em)
    {
        $this->security = $security;
        $this->em = $em;

        if($this->security->getUser()->getSelectedOrganisation() === null){
           $organisation =  $em->getRepository(Organisation::class)->findOneBy(array());

           if($organisation){
               $this->selectOrgansiation($organisation);
           }

        }
    }

    public function selectOrgansiation(Organisation $organisation){
        $user = $this->security->getUser()->setSelectedOrganisation($organisation);
        $this->em->persist($user);
        $this->em->flush();
    }


}