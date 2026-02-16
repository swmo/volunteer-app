<?php

namespace App\DataFixtures;

use App\Entity\Enrollment;
use App\Entity\Mission;
use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EnrollmentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $firstNames = [
            'Lena', 'Mia', 'Noah', 'Elias', 'Sophie',
            'Liam', 'Emma', 'Jonas', 'Lea', 'Nina',
            'Mara', 'Tim', 'Jan', 'Nora', 'Luca',
        ];

        $lastNames = [
            'Muster', 'Keller', 'Meier', 'Schmid', 'Wenger',
            'Fischer', 'Gerber', 'Lenz', 'Huber', 'Kunz',
        ];

        $cities = ['Burgdorf', 'Bern', 'Thun', 'Langenthal', 'Zollikofen'];
        $shirtSizes = ['XS', 'S', 'M', 'L', 'XL'];

        $projects = [
            'Project_Stadtlauf_2019',
            'Project_Stadtlauf_2020',
            'Project_Tennisturnier_Grindelwald_2020',
            'Project_Berner_Tennis_2020',
        ];

        foreach ($projects as $projectIndex => $projectReference) {
            $project = $this->getReference($projectReference, Project::class);

            for ($i = 1; $i <= 10; $i++) {
                $firstname = $firstNames[($projectIndex + $i) % count($firstNames)];
                $lastname = $lastNames[($projectIndex * 2 + $i) % count($lastNames)];
                $city = $cities[($projectIndex + $i) % count($cities)];
                $hasTshirt = 0 === $i % 2;

                $mission1 = $this->getReference(
                    sprintf('Mission_%s_%02d', $projectReference, (($i - 1) % 10) + 1),
                    Mission::class
                );

                $mission2 = null;
                if (0 === $i % 3) {
                    $mission2 = $this->getReference(
                        sprintf('Mission_%s_%02d', $projectReference, (($i + 2) % 10) + 1),
                        Mission::class
                    );
                }

                $enrollment = new Enrollment();
                $enrollment
                    ->setFirstname($firstname)
                    ->setLastname($lastname)
                    ->setEmail(strtolower(sprintf('%s.%s+%s%d@example.org', $firstname, $lastname, $projectIndex, $i)))
                    ->setStreet(sprintf('Hauptstrasse %d', 10 + ($projectIndex * 20) + $i))
                    ->setZip((string) (3000 + ($projectIndex * 100) + $i))
                    ->setCity($city)
                    ->setMobile(sprintf('079 %03d %02d %02d', 200 + $projectIndex, $i, 10 + $i))
                    ->setBirthday(new \DateTimeImmutable(sprintf('-%d years', 18 + (($projectIndex + $i) % 35))))
                    ->setComment('Fixture Anmeldung fuer Test und Entwicklung.')
                    ->setConfirmToken(sprintf('fixture-token-%s-%02d', $projectReference, $i))
                    ->setStatus(['confirmed'])
                    ->setHasTshirt($hasTshirt)
                    ->setTshirtsize($hasTshirt ? $shirtSizes[($projectIndex + $i) % count($shirtSizes)] : '')
                    ->setProject($project)
                    ->setMissionChoice01($mission1)
                    ->setMissionChoice02($mission2);

                $manager->persist($enrollment);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            MissionFixtures::class,
        ];
    }
}
