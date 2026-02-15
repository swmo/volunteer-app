<?php

namespace App\DataFixtures;

use App\Entity\Enrollment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EnrollmentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $enrollment = new Enrollment();
        $enrollment
        ->setBirthday(new \Datetime())
        ->setCity('Bern')
        ->setComment('Bitte kurz anrufen, bin verunsicher ob dieser Einsatz für mich ok ist.')
        ->setConfirmToken('asjfdsjfjsalfjasl')
        ->setEmail('moses.tschanz@gmail.com')
        ->setFirstname('Moses')
        ->setLastname('Tschanz')
        ->setMissionChoice01($this->getReference('Mission_Streckenposten'))
        ->setMobile('088 999 999 99')
        ->setZip(3600)
        ->setStreet('Teststrasse 10')
        ->setHasTshirt(true)
        ->setProject($this->getReference('Project_Stadtlauf_2020'))
        ;
        $manager->persist($enrollment);
        
    
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return array(
            MissionFixtures::class,
        );
    }
}
