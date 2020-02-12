<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/admin/user/list", name="admin_user_list")
     */
    public function list()
    {
        return $this->render('admin/user/list.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
