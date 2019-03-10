<?php

namespace App\DataFixtures;

use App\Entity\Mission;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class MissionFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 2019; $i < 2024; $i++) {
            $mission = new Mission();
            $mission->setName('Jahr '.$i);
            $manager->persist($mission);
        }
        $manager->flush();

    }
}
