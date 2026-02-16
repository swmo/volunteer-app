<?php

namespace App\Controller\Admin;

use App\Entity\Enrollment;
use App\Entity\Mission;
use App\Entity\Person;
use App\Entity\Project;
use App\Manager\UserOrganisationManager;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Loggable\Entity\LogEntry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class LogController extends AbstractController
{
    #[Route("/admin/log/list", name: "admin_log_list")]
    public function list(EntityManagerInterface $em, UserOrganisationManager $userOrganisationManager)
    {
        $organisation = $userOrganisationManager->getSelectedOrganisation();
        if (null === $organisation) {
            return $this->render('admin/log/list.html.twig', [
                'logEntries' => [],
            ]);
        }

        $projectIdRows = $em->createQueryBuilder()
            ->select('p.id')
            ->from(Project::class, 'p')
            ->andWhere('p.organisation = :organisation')
            ->setParameter('organisation', $organisation)
            ->getQuery()
            ->getScalarResult();
        $projectIds = array_column($projectIdRows, 'id');

        $missionIds = [];
        $enrollmentIds = [];
        if ([] !== $projectIds) {
            $missionIdRows = $em->createQueryBuilder()
                ->select('m.id')
                ->from(Mission::class, 'm')
                ->andWhere('m.project IN (:projectIds)')
                ->setParameter('projectIds', $projectIds)
                ->getQuery()
                ->getScalarResult();
            $missionIds = array_column($missionIdRows, 'id');

            $enrollmentIdRows = $em->createQueryBuilder()
                ->select('e.id')
                ->from(Enrollment::class, 'e')
                ->andWhere('e.project IN (:projectIds)')
                ->setParameter('projectIds', $projectIds)
                ->getQuery()
                ->getScalarResult();
            $enrollmentIds = array_column($enrollmentIdRows, 'id');
        }

        $personIdRows = $em->createQueryBuilder()
            ->select('person.id')
            ->from(Person::class, 'person')
            ->leftJoin('person.organisations', 'org')
            ->andWhere('org = :organisation')
            ->setParameter('organisation', $organisation)
            ->getQuery()
            ->getScalarResult();
        $personIds = array_column($personIdRows, 'id');

        $qb = $em->getRepository(LogEntry::class)->createQueryBuilder('log');
        $or = $qb->expr()->orX();

        if ([] !== $projectIds) {
            $or->add($qb->expr()->andX(
                $qb->expr()->eq('log.objectClass', ':projectClass'),
                $qb->expr()->in('log.objectId', ':projectIds')
            ));
            $qb->setParameter('projectClass', Project::class);
            $qb->setParameter('projectIds', array_map('strval', $projectIds));
        }

        if ([] !== $missionIds) {
            $or->add($qb->expr()->andX(
                $qb->expr()->eq('log.objectClass', ':missionClass'),
                $qb->expr()->in('log.objectId', ':missionIds')
            ));
            $qb->setParameter('missionClass', Mission::class);
            $qb->setParameter('missionIds', array_map('strval', $missionIds));
        }

        if ([] !== $enrollmentIds) {
            $or->add($qb->expr()->andX(
                $qb->expr()->eq('log.objectClass', ':enrollmentClass'),
                $qb->expr()->in('log.objectId', ':enrollmentIds')
            ));
            $qb->setParameter('enrollmentClass', Enrollment::class);
            $qb->setParameter('enrollmentIds', array_map('strval', $enrollmentIds));
        }

        if ([] !== $personIds) {
            $or->add($qb->expr()->andX(
                $qb->expr()->eq('log.objectClass', ':personClass'),
                $qb->expr()->in('log.objectId', ':personIds')
            ));
            $qb->setParameter('personClass', Person::class);
            $qb->setParameter('personIds', array_map('strval', $personIds));
        }

        if (0 === $or->count()) {
            $qb->andWhere('1 = 0');
        } else {
            $qb->andWhere($or);
        }

        return $this->render('admin/log/list.html.twig', [
            'logEntries' => $qb->orderBy('log.id', 'DESC')->getQuery()->getResult(),
        ]);
    }
}
