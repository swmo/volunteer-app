<?php

namespace App\DataFixtures;

use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProjectFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $project = new Project();
        $project->setName("Stadtlauf 2019");
        $project->setOrganisation($this->getReference('Organisation_Stadtlauf_Burgdorf'));
        
        $manager->persist($project);
        $manager->flush();

        $this->addReference('Project_Stadtlauf_2019', $project);

        $project = new Project();
        $project->setName("Stadtlauf 2020");
        $project->setOrganisation($this->getReference('Organisation_Stadtlauf_Burgdorf'));
        $project->setIsEnabled(true);
        $manager->persist($project);
        $manager->flush();

        $this->addReference('Project_Stadtlauf_2020', $project);

        $project = new Project();
        $project->setName("Tennisturnier Grindelwald 2020");
        $project->setOrganisation($this->getReference('Organisation_Tennisverein_Burgdorf'));
        $project->setIsEnabled(true);
        $manager->persist($project);
        $manager->flush();
        $this->addReference('Project_Tennisturnier_Grindelwald_2020', $project);

        $project = new Project();
        $project->setName("Berner Tennis 2020");
        $project->setOrganisation($this->getReference('Organisation_Tennisverein_Burgdorf'));
        $project->setIsEnabled(true);
        $manager->persist($project);
        $manager->flush();

        $this->addReference('Project_Berner_Tennis_2020', $project);
    }

    public function getDependencies()
    {
        return array(
            OrganisationFixtures::class,
        );
    }
}
