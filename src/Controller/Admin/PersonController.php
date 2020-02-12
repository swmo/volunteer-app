<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class PersonController extends AbstractController
{
    /**
     * @Route("/person/list", name="admin_person_list")
     */
    public function list()
    {
        return $this->render('admin/person/list.html.twig', [
            'controller_name' => 'PersonController',
        ]);
    }


}
