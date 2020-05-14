<?php

namespace App\Controller\Admin;

use App\Entity\Enrollment;
use App\Entity\Person;
use App\Manager\UserOrganisationManager;
use App\Repository\PersonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/admin")
 */
class PersonController extends AbstractController
{
    /**
     * @Route("/person/list", name="admin_person_list")
     */
    public function list(PersonRepository $personRepository, UserOrganisationManager $userOrganisationManager)
    {
        return $this->render('admin/person/list.html.twig', [
            'persons' =>  $personRepository->findByOrganisation($userOrganisationManager->getSelectedOrganisation()),
        ]);
    }

    /**
     * @Route("/person/delete/{id}", name="admin_person_delete")
     */
    public function delete(Person $person, EntityManagerInterface $em)
    {
        $msg = 'Person wurde gelöscht ' . $person->getId() . ': ' . $person->getLastname() . ' ' . $person->getFirstname(); 
        $em->remove($person);
        $em->flush();

        $this->addFlash(
            'success',
            $msg
        );

        return $this->redirectToRoute('admin_person_list');
    }

    /**
     * @Route("/person/export/", name="admin_person_export")
     */
    public function export(Request $request, EntityManagerInterface $em, UserOrganisationManager $userOrganisationManager)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $persons = $em->getRepository(Person::class)->findByOrganisation($userOrganisationManager->getSelectedOrganisation());

        $i = 2;
        $sheet->setCellValue('A1', 'Id');
        $sheet->setCellValue('B1', 'Nachname');
        $sheet->setCellValue('C1', 'Vorname');
        $sheet->setCellValue('D1', 'Strasse');
        $sheet->setCellValue('E1', 'Plz');
        $sheet->setCellValue('F1', 'Ort');

        $sheet->setCellValue('G1', 'EMail');
        $sheet->setCellValue('H1', 'Mobile');
        $sheet->setCellValue('I1', 'Bemerkung');
        $sheet->setCellValue('J1', 'Projekte / Anmeldungen');
     
        foreach($persons as $person){
            /** @var Person $person */
            $sheet->setCellValue('A'.$i, $person->getId());
            $sheet->setCellValue('B'.$i, $person->getLastname());
            $sheet->setCellValue('C'.$i, $person->getFirstname());
            $sheet->setCellValue('D'.$i, $person->getStreet());
            $sheet->setCellValue('E'.$i, $person->getZip());
            $sheet->setCellValue('F'.$i, $person->getCity());
            $sheet->setCellValue('G'.$i, $person->getEmail());
            $sheet->setCellValue('H'.$i, $person->getMobile());
            $sheet->setCellValue('I'.$i, $person->getRemark());

            $enrollments = $em->getRepository(Enrollment::class)->findByOrganisationAndPerson($userOrganisationManager->getSelectedOrganisation(),$person);

            $cellJ = '';
            foreach($enrollments as $enrollment){
                if($enrollment->getMissionChoice01()){
                    $cellJ .= $enrollment->getProject()->getName() . " - " . $enrollment->getMissionChoice01() . "\n";
                }
                if($enrollment->getMissionChoice02()){
                    $cellJ .= $enrollment->getProject()->getName() . " - " . $enrollment->getMissionChoice02() . "\n";
                }
            }
            $sheet->setCellValue('J'.$i, $cellJ);
            $sheet->getStyle('J'.$i)->getAlignment()->setWrapText(true);

           // $sheet->setCellValue('J'.$i, $person->get());
           $i++;
        }
        // Create your Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);
          
        // Create a Temporary file in the system
        $now = new \DateTime();

        $fileName = $now->format('Ymd').'_'.$userOrganisationManager->getSelectedOrganisation()->getId().'_persons_export.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    /**
     * @Route("/person/emailgroup", name="admin_person_emailgroup")
     */
    public function emailgroup(PersonRepository $personRepository, UserOrganisationManager $userOrganisationManager,ValidatorInterface $validator)
    {

        $persons = $personRepository->findByOrganisation($userOrganisationManager->getSelectedOrganisation());

        $emails = array();

        $emailConstraint = new Assert\Email();
        // all constraint "options" can be set this way
        $emailConstraint->message = 'Invalid email address';
    

        foreach($persons as $person){

             // use the validator to validate the value
            $errors = $validator->validate(
                $person->getEmail(),
                $emailConstraint
            );
        
            if (0 === count($errors) && $person->getEmail() <> "") {

                $emails[$person->getEmail()][] = $person;
            } 
           
        }


        return $this->render('admin/person/emailgroup.html.twig', [
            'emails' =>  $emails,
        ]);
    }



}
