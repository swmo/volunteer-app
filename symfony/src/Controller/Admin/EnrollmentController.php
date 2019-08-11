<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Enrollment;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Admin\EnrollmentFormType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use App\Entity\Mission;

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
    public function export(Request $request, EntityManagerInterface $em)
    {
        // liste mit vorname, nachname, von, bis, h, Einsatztort -> sortiert nach Einsatzort

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $missions = $em->getRepository(Mission::class)->findAll();


        $i = 1;
        foreach($missions as $mission){
            /** @var Mission $mission **/
            foreach($mission->getEnrollments() as $enrollment){
                /** @var Enrollment $enrollment **/
                $sheet->setCellValue('A'.$i, $enrollment->getFirstname());
                $sheet->setCellValue('B'.$i, $enrollment->getLastname());
                $sheet->setCellValue('C'.$i, $mission->getName());
                $i++;
            }
            
        }

        $sheet->setTitle("Export");



        // Create your Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);
                
        // Create a Temporary file in the system
        $fileName = 'my_first_excel_symfony4.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
        
    }
}