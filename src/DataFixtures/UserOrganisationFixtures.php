<?php

namespace App\DataFixtures;

use App\Entity\UserOrganisation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserOrganisationFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $userOrganisation = new UserOrganisation();
        $userOrganisation->setAppuser($this->getReference('User_Tennisverein_Burgdorf'));
        $userOrganisation->setOrganisation($this->getReference('Organisation_Tennisverein_Burgdorf'));
        $manager->persist($userOrganisation);

        $userOrganisation = new UserOrganisation();
        $userOrganisation->setAppuser($this->getReference('User_Stadtlauf_Burgdorf'));
        $userOrganisation->setOrganisation($this->getReference('Organisation_Stadtlauf_Burgdorf'));
        $manager->persist($userOrganisation);

        // Admin with both Organisations Rights:
        $userOrganisation = new UserOrganisation();
        $userOrganisation->setAppuser($this->getReference('User_Admin'));
        $userOrganisation->setOrganisation($this->getReference('Organisation_Stadtlauf_Burgdorf'));
        $manager->persist($userOrganisation);
        
        $userOrganisation = new UserOrganisation();
        $userOrganisation->setAppuser($this->getReference('User_Admin'));
        $userOrganisation->setOrganisation($this->getReference('Organisation_Tennisverein_Burgdorf'));
        $manager->persist($userOrganisation);
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            OrganisationFixtures::class,
        );
    }
}
