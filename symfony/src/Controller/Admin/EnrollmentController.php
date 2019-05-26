<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Enrollment;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Admin\EnrollmentFormType;

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
        $enrollments = $em->getRepository(Enrollment::class)->findBy(array(), array('firstname' => 'ASC'));;

        return $this->render('admin/enrollment/list.html.twig', [
            'enrollments' => $enrollments,
        ]);
    }

    /**
     * @Route("/enrollment/edit/{id}", name="admin_enrollment_edit")
     */
    public function edit(Enrollment $enrollment, EntityManagerInterface $em, Request $request) 
    {
        $form = $this->createForm(EnrollmentFormType::class,$enrollment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mutatedEndrollment = $form->getData();

            $em->persist($mutatedEndrollment);
            $em->flush();       

            $this->addFlash(
                'success',
                'Anmeldung wurde gespeichert'
            );

            return $this->redirectToRoute('admin_enrollment_list');
        }

        return $this->render('admin/enrollment/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/enrollment/export/{type}", name="admin_enrollment_export")
     */
    public function export(Request $request)
    {
        // liste mit vorname, nachname, von, bis, h, Einsatztort -> sortiert nach Einsatzort
        
    }
}