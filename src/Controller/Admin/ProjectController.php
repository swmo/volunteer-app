<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Admin\ProjectFormType;
use App\Utils\MergePerson;
use Doctrine\ORM\EntityManagerInterface;
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
        return $this->render('admin/project/create.html.twig', [
            'formCreate' => $formCreate->createView(),
        ]);

    
    }

    /**
     * todo: create an Command for this and not over the Frontend (ugly because its only needed for special needs, as example for migrations)
     * @Route("/project/{id}/mergetoperson", name="admin_project_mergetoperson")
     */
    public function mergetoperson(Project $project, EntityManagerInterface $em, MergePerson $mergePerson)
    {
        $mergePerson->mergeProject($project);

        foreach($msgs = $mergePerson->getMsgs() as $msg){
            $this->addFlash($msg[0],$msg[1]);
        }

        return $this->redirectToRoute('admin_project_list');
    }




}
