<?php

namespace App\DataFixtures;

use App\Entity\Organisation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class OrganisationFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $organisation = new Organisation();
        $organisation->setName('Stadtlauf Burgdorf');
        $this->addReference('Organisation_Stadtlauf_Burgdorf', $organisation);
        $manager->persist($organisation);
        $manager->flush();

    }
}
