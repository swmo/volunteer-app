<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use App\Entity\Enrollment;
use App\Entity\Person;
use App\Utils\MonologDbHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    private const ACTIVE_MISSION_TOOLTIP = 'Es werden nur Anmeldungen in aktiven Einsätzen gezählt. Gelöschte Anmeldungen sind ausgeschlossen.';

    #[Route("/admin", name: "admin_dashboard")]

    public function dashboard(MonologDbHandler $db, EntityManagerInterface $em)
    {
        $persons = $em->getRepository(Person::class)->findAll();
        $selectedOrganisation = $this->getUser()?->getSelectedOrganisation();
        $projects = [];
        $projectStats = [];
        $recentEnrollments = [];
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

            $recentEnrollmentCandidates = $em->getRepository(Enrollment::class)
                ->createQueryBuilder('e')
                ->leftJoin('e.project', 'p')
                ->addSelect('p')
                ->leftJoin('e.missionChoice01', 'm1')
                ->addSelect('m1')
                ->leftJoin('e.missionChoice02', 'm2')
                ->addSelect('m2')
                ->andWhere('p.organisation = :organisation')
                ->setParameter('organisation', $selectedOrganisation)
                ->orderBy('e.id', 'DESC')
                ->setMaxResults(40)
                ->getQuery()
                ->getResult();

            $recentEnrollments = array_slice(array_values(array_filter(
                $recentEnrollmentCandidates,
                fn (Enrollment $enrollment): bool => !in_array('deleted', $enrollment->getStatus() ?? [], true)
            )), 0, 8);
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

            $enrolledPersons = $filledSlots;
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
            'recentEnrollments' => $recentEnrollments,
            'projectStats' => $projectStats,
            'totalOpenEnrollments' => $totalOpenEnrollments,
            'totalEnrolledPersons' => $totalEnrolledPersons,
            'activeMissionCountTooltip' => self::ACTIVE_MISSION_TOOLTIP,
        ]);
    }

}
