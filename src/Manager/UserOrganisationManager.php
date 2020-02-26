<?php

namespace App\Manager;

use App\Entity\Organisation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class UserOrganisationManager 
{
    protected $security = null;
    protected $em = null;

    public function __construct(Security $security, EntityManagerInterface $em)
    {
        $this->security = $security;
        $this->em = $em;
    }

    public function selectOrgansiation(Organisation $organisation){
        $user = $this->security->getUser()->setSelectedOrganisation($organisation);
        $this->em->persist($user);
        $this->em->flush();
    }

    public function getSelectedOrganisation(): ? Organisation 
    {
        return $this->security->getUser()->getSelectedOrganisation();
    }

    public function getUsers()
    { 
        return $this->getSelectedOrganisation()->getUserOrganisations();
    }

}