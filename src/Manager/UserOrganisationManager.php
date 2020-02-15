<?php

namespace App\Manager;

use App\Entity\Organisation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class UserOrganisationManager 
{
    protected $security = null;
    protected $em = null;
    protected $organisation = null;

    public function __construct(Security $security, EntityManagerInterface $em)
    {
        $this->security = $security;
        $this->em = $em;
        
        /*
        Prüft ob der User bereits eine Organisation ausgewählt hat, falls nicht so wird ihm eine zugeteilt
        */
        if(($this->organisation = $this->security->getUser()->getSelectedOrganisation()) === null){
        
           if($this->organisation =  $em->getRepository(Organisation::class)->findOneBy(array())){
               $this->selectOrgansiation($this->organisation);
           }

        }
    }

    public function selectOrgansiation(Organisation $organisation){
        $user = $this->security->getUser()->setSelectedOrganisation($organisation);
        $this->em->persist($user);
        $this->em->flush();
        $this->organisation = $organisation;
    }

    public function getSelectedOrganisation(){
        return $this->organisation;
    }

    public function getUsers(){
        return $this->getSelectedOrganisation()->getUserOrganisations();
    }

}