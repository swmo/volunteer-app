<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MissionController extends AbstractController
{

    /**
     * @Route("/mission/create", name="mission_create")
     */
    public function create()
    {
        return $this->render('mission/create.html.twig', [
            'controller_name' => 'Create a new Mission',
        ]);
    }

}
