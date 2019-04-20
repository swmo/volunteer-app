<?php

namespace App\DataFixtures;

use App\Entity\Mission;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MissionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $mission = new Mission();
        $mission->setName('Chip- Entnahme');
        $mission->setShortDescription(' 
            Aufgabe: Du bist beim Zieleingang und nimmst jedem Läufer den Zeitmessungs- Chip ab.
            <br />
            Anforderung: Du kannst schnell und ohne Berührungsängsten anpacken.
        ');
        $mission->setProject($this->getReference('Project_Stadtlauf_2019'));
        $manager->persist($mission);
        $manager->flush();

        
        $mission = new Mission();
        $mission->setName('Aufbau');
        $mission->setProject($this->getReference('Project_Stadtlauf_2019'));
        $manager->persist($mission);
        $manager->flush();
        $mission = new Mission();
        $mission->setName('Streckenposten');
        $mission->setProject($this->getReference('Project_Stadtlauf_2019'));
        $manager->persist($mission);
        $manager->flush();
        $mission = new Mission();
        $mission->setName('Anmeldung');
        $mission->setProject($this->getReference('Project_Stadtlauf_2019'));
        $manager->persist($mission);
        $manager->flush();
        $mission = new Mission();
        $mission->setName('Verpflegung Läufer');
        $mission->setProject($this->getReference('Project_Stadtlauf_2019'));
        $manager->persist($mission);
        $manager->flush();
        $mission = new Mission();
        $mission->setName('Festwirtschaft');
        $mission->setProject($this->getReference('Project_Stadtlauf_2019'));
        $manager->persist($mission);
        $manager->flush();
        $mission = new Mission();
        $mission->setName('Abbau');
        $mission->setProject($this->getReference('Project_Stadtlauf_2019'));
        $manager->persist($mission);
        $manager->flush();
        $mission = new Mission();
        $mission->setName('Maskottchen "Cooly"');
        $mission->setProject($this->getReference('Project_Stadtlauf_2019'));
        $manager->persist($mission);
        $manager->flush();
        $mission = new Mission();
        $mission->setName('Verpacken der Läufergeschenke');
        $mission->setProject($this->getReference('Project_Stadtlauf_2019'));
        $manager->persist($mission);
        $manager->flush();

    }

    public function getDependencies()
    {
        return array(
            ProjectFixtures::class,
        );
    }
}
