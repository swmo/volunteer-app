<?php

namespace App\DataFixtures;

use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ProjectFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
         $project = new Project();
         $project->setName("Stadtlauf 2019");
         $manager->persist($project);

        $manager->flush();
    }
}
