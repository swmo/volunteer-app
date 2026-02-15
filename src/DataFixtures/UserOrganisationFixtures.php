<?php

namespace App\DataFixtures;

use App\Entity\UserOrganisation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserOrganisationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
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

    public function getDependencies(): array
    {
        return array(
            UserFixtures::class,
            OrganisationFixtures::class,
        );
    }
}
