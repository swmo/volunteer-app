<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use App\Entity\Person;
use App\Utils\MonologDbHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route("/admin", name: "admin_dashboard")]

    public function dashboard(MonologDbHandler $db, EntityManagerInterface $em)
    {
        $persons = $em->getRepository(Person::class)->findAll();
        $selectedOrganisation = $this->getUser()?->getSelectedOrganisation();
        $projects = [];
        $projectStats = [];
        $totalOpenEnrollments = 0;
        $totalEnrolledPersons = 0;

        if ($selectedOrganisation !== null) {
            $projects = $em->getRepository(Project::class)->findBy(
                [
                    'organisation' => $selectedOrganisation,
                    'isEnabled' => true,
                ],
                ['name' => 'ASC']
            );
        }

        foreach ($projects as $project) {
            $requiredSlots = 0;
            $filledSlots = 0;

            foreach ($project->getMissions() as $mission) {
                if (!$mission->getIsEnabled()) {
                    continue;
                }

                $requiredSlots += (int) $mission->getRequiredVolunteers();
                $filledSlots += (int) $mission->countEnrolledVolunteers();
            }

            $enrolledPersons = $project->getEnrollments()->count();
            $openEnrollments = max(0, $requiredSlots - $filledSlots);

            $totalEnrolledPersons += $enrolledPersons;
            $totalOpenEnrollments += $openEnrollments;

            $projectStats[] = [
                'project' => $project,
                'enrolledPersons' => $enrolledPersons,
                'openEnrollments' => $openEnrollments,
            ];
        }

        return $this->render('admin/dashboard/index.html.twig', [
            'controller_name' => 'Test',
            'persons' => $persons,
            'projectStats' => $projectStats,
            'totalOpenEnrollments' => $totalOpenEnrollments,
            'totalEnrolledPersons' => $totalEnrolledPersons,
        ]);
    }

}
