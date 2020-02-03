<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ProjectFormType;
use Doctrine\ORM\EntityManagerInterface;

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
        $missions = $em->getRepository(Project::class)->findAll();
        
        return $this->render('admin/project/list.html.twig', [
            'missions' => $missions,
        ]);
    }


}
