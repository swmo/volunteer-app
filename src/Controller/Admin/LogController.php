<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LogController extends AbstractController
{
    /**
     * @Route("/admin/log/list", name="admin_log_list")
     */
    public function list()
    {
        return $this->render('admin/log/list.html.twig', [
            'controller_name' => 'LogController',
        ]);
    }
}
