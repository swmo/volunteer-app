<?php

namespace App\Controller\Admin;

use App\Entity\Mission;
use App\Entity\Project;
use App\Form\Admin\ProjectCopyFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Admin\ProjectFormType;
use App\Utils\MergePerson;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin")
 */
class ProjectController extends AbstractController
{

    /**
     * @Route("/project/list", name="admin_project_list")
     */
    public function list(EntityManagerInterface $em)
    {
        $projects = $em->getRepository(Project::class)->findAll();
        
        return $this->render('admin/project/list.html.twig', [
            'projects' => $projects,
        ]);
    }

    /**
     * @Route("/project/edit/{id}", name="admin_project_edit")
     */
    public function edit(Project $project, EntityManagerInterface $em, Request $request)
    {
        
        $form = $this->createForm(ProjectFormType::class,$project);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
           
            $project = $form->getData();

            $em->persist($project);
            $em->flush();       

            $this->addFlash(
                'success',
                'Projekt wurde editiert'
            );
        
            return $this->redirectToRoute('admin_project_list');
        }

        return $this->render('admin/project/edit.html.twig', [
            'form' => $form->createView()
        ]);

    }


        /**
     * @Route("/project/create", name="admin_project_create")
     */
    public function create(EntityManagerInterface $em, Request $request)
    {
        
        $formCreate = $this->createForm(ProjectFormType::class);
        $formCreate->handleRequest($request);


        if ($formCreate->isSubmitted() && $formCreate->isValid()) {
           
            $project = $formCreate->getData();

            $em->persist($project);
            $em->flush();       

            $this->addFlash(
                'success',
                'Projekt wurde erstellt'
            );
        
            return $this->redirectToRoute('admin_project_list');
        }

        $formCopy = $this->createForm(ProjectCopyFormType::class);
        $formCopy->handleRequest($request);

        if ($formCopy->isSubmitted() && $formCopy->isValid()) {
            $data = $formCopy->getData();
            /** @var Project $fromProject$ */
           // $fromProject =  $em->getRepository(Project::class)->findOneBy(['id'=>$data['project']->getId()]);
            $fromProject = $data['project'];
            $newProject = new Project();
            $newProject
                ->setName($data['name'])
                ->isEnabled(false);
            $newProject->setOrganisation($fromProject->getOrganisation());

            foreach($fromProject->getMissions() as $fromMission){
                /** @var Mission $newMission */
                $newMission = new Mission();
                $newMission->setProject($newProject);
                $newMission
                    ->setIsEnabled(false)
                    ->setCalendarEventDescription($fromMission->getCalendarEventDescription())
                    ->setMeetingPoint($fromMission->getMeetingPoint())
                    ->setRequiredVolunteers($fromMission->getRequiredVolunteers())
                    ->setStart($fromMission->getStart())
                    ->setEnd($fromMission->getEnd())
                    ->setImage($fromMission->getImage())
                    ->setShortDescription($fromMission->getShortDescription())
                    ->setName(sprintf('copied: %s ',$fromMission->getName()))
                    ;
                $em->persist($newMission);
           }

            $this->addFlash(
                'success',
                sprintf('Projekt %s wurde erstellt',$newProject->getName())
            );

            $em->persist($newProject);
            $em->flush();

            return $this->redirectToRoute('admin_project_list');
        }

        return $this->render('admin/project/create.html.twig', [
            'formCreate' => $formCreate->createView(),
            'formCopy' => $formCopy->createView(),
        ]);

    
    }

    /**
     * todo: create an Command for this and not over the Frontend (ugly because its only needed for special needs, as example for migrations)
     * @Route("/project/{id}/mergetoperson", name="admin_project_mergetoperson")
     */
    public function mergetoperson(Project $project, EntityManagerInterface $em, MergePerson $mergePerson)
    {

        throw new Exception("do not use this anymore");
        /*
        $mergePerson->mergeProject($project);

        foreach($msgs = $mergePerson->getMsgs() as $msg){
            $this->addFlash($msg[0],$msg[1]);
        }

        return $this->redirectToRoute('admin_project_list');
        */
    }




}
