<?php

namespace App\Controller;

use App\Entity\Person;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{



    /**
     * @Route("/dashboard", name="dashboard")
     * @Route("/", name="home")
     */
    public function dashboard()
    {
        $persons = $this->getDoctrine()
            ->getRepository(Person::class)
            ->findAll();

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'Test',
            'persons' => $persons
        ]);
    }
}
