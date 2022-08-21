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
use App\Entity\Project;

/**
 * @Route("/admin")
 */
class EnrollmentController extends AbstractController
{
    /**
     * @Route("/enrollment/list/project/{id}", name="admin_enrollment_list_by_project")
     */
    public function index(EntityManagerInterface $em, Project $project = null) 
    {
        
        $enrollments = $em->getRepository(Enrollment::class)->findBy(array('project' => $project), array('firstname' => 'ASC'));;

        return $this->render('admin/enrollment/list.html.twig', [
            'enrollments' => $enrollments,
            'project' => $project
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

            return $this->redirectToRoute('admin_enrollment_list_by_project',[
                'id' => $mutatedEndrollment->getProject()->getId()
            ]);
        }

        return $this->render('admin/enrollment/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/enrollment/export/{project}/{type}", name="admin_enrollment_export")
     */
    public function export(Request $request, EntityManagerInterface $em, Project $project)
    {
        // liste mit vorname, nachname, von, bis, h, Einsatztort -> sortiert nach Einsatzort

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        
        $missions = $em->getRepository(Mission::class)->findAllByProject($project);


        $i = 2;
        $sheet->setCellValue('A1', 'Einsatz');
        $sheet->setCellValue('B1', 'Vorname');
        $sheet->setCellValue('C1', 'Nachname');
        $sheet->setCellValue('D1', 'Von');
        $sheet->setCellValue('E1', 'Bis');
        $sheet->setCellValue('F1', 'Arbeitsstunden (dezimal)');

        $sheet->setCellValue('G1', 'EMail');
        $sheet->setCellValue('H1', 'Mobile');
        $sheet->setCellValue('I1', 'T-Shirt von letzem Jahr dabei?');
        $sheet->setCellValue('J1', 'T-Shirt Grösse');

        foreach($missions as $mission){
            /** @var Mission $mission **/
            foreach($mission->getEnrollments() as $enrollment){
                /** @var Enrollment $enrollment **/

                $sheet->setCellValue('A'.$i, $mission->getName());
                $sheet->setCellValue('B'.$i, $enrollment->getFirstname());
                $sheet->setCellValue('C'.$i, $enrollment->getLastname());

                if($enrollment->getMissionChoice01()){

                    if($mission->getId() == $enrollment->getMissionChoice01()->getId()){
                        $von = $enrollment->getStartTimeMissionChoice01();
                    }
    
                    if($mission->getId() == $enrollment->getMissionChoice01()->getId()){
                        $bis = $enrollment->getEndTimeMissionChoice01();
                    }
                    if($mission->getId() == $enrollment->getMissionChoice01()->getId()){
                        $workhours = $enrollment->getWorkingTimeMissionChoice01();
                    }

                }

                if($enrollment->getMissionChoice02()){
                 
                    if($mission->getId() == $enrollment->getMissionChoice02()->getId()){
                        $von = $enrollment->getStartTimeMissionChoice02();
                    }  
                    
                    if($mission->getId() == $enrollment->getMissionChoice02()->getId()){
                        $bis = $enrollment->getEndTimeMissionChoice02();
                    }
                    if($mission->getId() == $enrollment->getMissionChoice02()->getId()){
                        $workhours = $enrollment->getWorkingTimeMissionChoice02();
                    }
                }


                $sheet->setCellValue('D'.$i, $von->format('H:i'));

               
             

                $sheet->setCellValue('E'.$i, $bis->format('H:i'));      
                
        
                $minutes = $workhours->format('%I');
                if($minutes > 0){
                    $minutes = $minutes / 60;
                }

                $workhoursDecimal = $workhours->format('%H') + $minutes;
              
                $sheet->setCellValue('F'.$i, $workhoursDecimal);
   
                $sheet->setCellValue('G'.$i, $enrollment->getEmail());
                $sheet->setCellValue('H'.$i, $enrollment->getMobile());
                $sheet->setCellValue('I'.$i, $enrollment->getHasTshirt() ? 'JA' : 'NEIN');
                $sheet->setCellValue('J'.$i, $enrollment->getTshirtsize());
            
                $i++;
            }
            
        }

        $sheet->setTitle("Export");



        // Create your Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);
                
        // Create a Temporary file in the system
        $now = new \DateTime();

        $fileName = $now->format('Ymd').'_burgdorfer_stadtlauf_missions_export.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
        
    }

    /**
     * @Route("/enrollment/mergetoperson/project/{id}", name="admin_enrollment_mergetoperson")
     */
    public function mergetoperson(Project $project, Request $request, EntityManagerInterface $em)
    {

    }

    
}