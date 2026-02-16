<?php

namespace App\Controller\Admin;

use App\Entity\Person;
use App\Utils\MonologDbHandler;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route("/admin", name: "admin_dashboard")]

    public function dashboard(MonologDbHandler $db, EntityManagerInterface $em)
    {
       
        
        $persons = $em->getRepository(Person::class)->findAll();

        //Bu$enrollments = $this->

        return $this->render('admin/dashboard/index.html.twig', [
            'controller_name' => 'Test',
            'persons' => $persons
        ]);
    }

}

