<?php

namespace App\DataFixtures;

use App\Entity\Mission;
use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MissionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $missionNames = [
            'Registration Desk',
            'Course Setup',
            'Course Marshal',
            'Refreshments',
            'Logistics',
            'Information Point',
            'Kids Area Support',
            'Technical Support',
            'On-site Coordination',
            'Wrap-up Team',
        ];

        $projectConfig = [
            'Project_Stadtlauf_2019' => [
                'label' => 'Stadtlauf 2019',
                'start' => '2019-09-14 08:00:00',
            ],
            'Project_Stadtlauf_2020' => [
                'label' => 'Stadtlauf 2020',
                'start' => '2020-09-12 08:00:00',
            ],
            'Project_Tennisturnier_Grindelwald_2020' => [
                'label' => 'Tennisturnier 2020',
                'start' => '2020-07-18 08:00:00',
            ],
            'Project_Berner_Tennis_2020' => [
                'label' => 'Berner Tennis 2020',
                'start' => '2020-08-22 08:00:00',
            ],
        ];

        foreach ($projectConfig as $projectReference => $config) {
            $project = $this->getReference($projectReference, Project::class);
            $baseStart = new \DateTimeImmutable($config['start']);

            foreach ($missionNames as $index => $missionName) {
                $start = $baseStart->modify(sprintf('+%d minutes', $index * 75));
                $end = $start->modify('+120 minutes');

                $mission = new Mission();
                $mission->setName(sprintf('%s - %s', $config['label'], $missionName));
                $mission->setShortDescription(sprintf('Volunteer mission "%s" for %s.', $missionName, $config['label']));
                $mission->setImage('logo.jpg');
                $mission->setStart($start);
                $mission->setEnd($end);
                $mission->setMeetingPoint('Main event desk');
                $mission->setRequiredVolunteers(4 + ($index % 5) * 2);
                $mission->setProject($project);

                $manager->persist($mission);

                $referenceKey = sprintf('Mission_%s_%02d', $projectReference, $index + 1);
                $this->addReference($referenceKey, $mission);

                if ('Project_Stadtlauf_2020' === $projectReference && 0 === $index) {
                    // Keep backwards-compatible reference name used by older fixtures.
                    $this->addReference('Mission_Streckenposten', $mission);
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProjectFixtures::class,
        ];
    }
}
