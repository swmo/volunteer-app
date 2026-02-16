<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Mission;
use App\Entity\Project;
use App\Form\Admin\MissionFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Entity\Enrollment;
use Gedmo\Loggable\Entity\LogEntry;

#[Route("/admin")]

class MissionController extends AbstractController
{

    /*
    private $logEntryRepository;

    public function __construct(LogEntryRepository $logEntryRepository)
    {
        $this->logEntryRepository = $logEntryRepository;
    }
    */

    #[Route("/mission/list", name: "admin_mission_list")]
#[Route("/mission/list/project/{id}", name: "admin_mission_list_by_project")]

    public function list(EntityManagerInterface $em, ?Project $project = null )
    {
        $projectsWithMissions = [];
        $projects = [];

        if ($project !== null) {
            $projects = [$project];
        } else {
            $projects = $em->getRepository(Project::class)->findAll();
            if (count($projects) === 0) {
                $this->addFlash(
                    'danger',
                    'No Project found please create a Project first!'
                );

                return $this->redirectToRoute('admin_project_list');
            }
        }

        foreach ($projects as $listProject) {
            $projectsWithMissions[] = [
                'project' => $listProject,
                'missions' => $em->getRepository(Mission::class)->findAllByProject($listProject),
            ];
        }

        return $this->render('admin/mission/list.html.twig', [
            'projectsWithMissions' => $projectsWithMissions,
            'project' => $project,
        ]);
    }

    #[Route("/mission/edit/{id}", name: "admin_mission_edit")]

    public function edit(Mission $mission, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(MissionFormType::class,$mission);
        $form->handleRequest($request);

        


        if ($form->isSubmitted() && $form->isValid()) {
            $mission = $form->getData();
            try {
                $this->handleMissionImageUpload($form, $mission);
            } catch (FileException $e) {
                $this->addFlash('danger', 'Bild konnte nicht hochgeladen werden.');

                return $this->render('admin/mission/edit.html.twig', [
                    'form' => $form->createView(),
                    'logEntries' => $em->getRepository(LogEntry::class)->getLogEntries($mission),
                ]);
            }

            $em->persist($mission);
            $em->flush();       

            $this->addFlash(
                'success',
                'Einsatz wurde gespeichert'
            );

            return $this->redirectToRoute('admin_mission_list');
        }

        return $this->render('admin/mission/edit.html.twig', [
            'form' => $form->createView(),
            'logEntries' => $em->getRepository(LogEntry::class)->getLogEntries($mission)
        ]);
    }

    #[Route("/mission/create", name: "admin_mission_create")]

    public function create(EntityManagerInterface $em, Request $request)
    {
        $form = $this->createForm(MissionFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $mission = $form->getData();
            try {
                $this->handleMissionImageUpload($form, $mission);
            } catch (FileException $e) {
                $this->addFlash('danger', 'Bild konnte nicht hochgeladen werden.');

                return $this->render('admin/mission/create.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            $em->persist($mission);
            $em->flush();       

            $this->addFlash(
                'success',
                'Einsatz wurde erstellt'
            );
        
            return $this->redirectToRoute('admin_mission_list');
        }
        return $this->render('admin/mission/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/mission/emailgroup/{id}", name: "admin_mission_emailgroup")]

    public function emailgroup(Mission $mission,EntityManagerInterface $em) 
    {
        $enrollments = $em->getRepository(Enrollment::class)->findByMission(
           $mission
        );

        return $this->render('admin/mission/emailgroup.html.twig', [
            'enrollments' => $enrollments,
        ]);
    }

    #[Route("/mission/toggle/activ/{id}", name: "admin_mission_toggle_activ")]

    public function toggleActiv(Mission $mission,EntityManagerInterface $em) 
    {
    
        $mission->setIsEnabled(!$mission->getIsEnabled());
        $em->persist($mission);
        $em->flush();

        return $this->redirectToRoute('admin_mission_list');
    }
    //todo: copyMissionToProject
    private function handleMissionImageUpload(FormInterface $form, Mission $mission): void
    {
        /** @var UploadedFile|null $imageFile */
        $imageFile = $form->get('imageFile')->getData();
        if (null === $imageFile) {
            return;
        }

        $binaryContent = @file_get_contents($imageFile->getPathname());
        if (false === $binaryContent) {
            throw new FileException('Failed to read uploaded image.');
        }

        $mission->setImageData($binaryContent);
        $mission->setImageMimeType($imageFile->getMimeType() ?: 'application/octet-stream');
        $mission->setImage($imageFile->getClientOriginalName());
    }

}
