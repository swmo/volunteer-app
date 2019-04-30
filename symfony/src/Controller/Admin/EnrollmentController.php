<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Enrollment;

/**
 * @Route("/admin")
 */
class EnrollmentController extends AbstractController
{
    /**
     * @Route("/enrollment/list", name="admin_enrollment_list")
     */
    public function index(EntityManagerInterface $em) 
    {

        $enrollments = $em->getRepository(Enrollment::class)->findAll();


        return $this->render('admin/enrollment/list.html.twig', [
            'enrollments' => $enrollments,
        ]);
    }
}
