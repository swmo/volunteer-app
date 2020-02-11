<?php

namespace App\Utils;

use Symfony\Component\Security\Core\Security;

class UserOrganisationManager 
{

    protected $security = null;

    public function __construct(Security $security)
    {
        $this->security = $security;

        echo 'UserOrganisationManager: ' . $this->security->getUser();

        exit;
    }

    public function selectOrgansiation($organisation){

    }

}