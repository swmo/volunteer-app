<?php

namespace App\Controller;

use App\Form\VolunteerFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Enrollment;
use App\Entity\Mission;
use Symfony\Component\Filesystem\Filesystem;

class VolunteerController extends AbstractController
{

    /**
     * @Route("/volunteer/enroll", name="volunteer_enroll")
     */
    public function enroll(EntityManagerInterface $em, Request $request, \Swift_Mailer $mailer)
    {
        $form = $this->createForm(VolunteerFormType::class);
        $missions = $em->getRepository(Mission::class)->findAll();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        
            $enrollment = $form->getData();
            $em->persist($enrollment);

            $fs = new Filesystem();

            //temporary folder, it has to be writable
            $tmpFolder = '/tmp/';

            //the name of your file to attach
            $fileName = 'Kalendereintrag_'.$enrollment->getId().'.ics';

        $icsContent = "BEGIN:VCALENDAR
VERSION:2.0
CALSCALE:GREGORIAN
BEGIN:VEVENT
SUMMARY:Helfereinsatz Stadtlauf Burgdorf
DTSTART;TZID=Europe/Zurich:".$enrollment->getMissionChoice01()->getStart()->format('Ymd\THis')."
DTEND;TZID=Europe/Zurich:".$enrollment->getMissionChoice01()->getEnd()->format('Ymd\THis')."
LOCATION:Hohengasse 25, 3400 Burgdorf
DESCRIPTION:Treffpunkt vor dem Kino Krone sofern nicht zu einem späteren Zeitpunkt noch anders kommuniziert.
STATUS:CONFIRMED
SEQUENCE:0
END:VEVENT
END:VCALENDAR";          

            //creation of the file on the server
            $icfFile = $fs->dumpFile($tmpFolder.$fileName, $icsContent);

            $message = (new \Swift_Message('Anmeldung | Burgdorfer Stadtlauf'));

            $image = ($message->embed(\Swift_Image::fromPath('/var/www/public/images/maria2.jpg')));
           // $image = "";

            $message
                ->setFrom('personal@burgdorfer-stadtlauf.ch')
                ->setTo($enrollment->getEmail())
                ->setBcc('personal@burgdorfer-stadtlauf.ch')
                ->attach(\Swift_Attachment::fromPath($tmpFolder.$fileName)->setContentType('text/calendar;charset=UTF-8;name="'.$fileName.'";method=REQUEST'))
                ->setBody(
                    $this->renderView(
                        // templates/emails/registration.html.twig
                        'emails/registration.html.twig',
                        ['enrollment' => $enrollment,
                        'image' => $image
                        ]
                    ),
                    'text/html'
                )
                /*
                * If you also want to include a plaintext version of the message
                ->addPart(
                    $this->renderView(
                        'emails/registration.txt.twig',
                        ['name' => $name]
                    ),
                    'text/plain'
                )
                */
            ;
    
            if($mailer->send($message)){
                $em->flush();
                //$this->addFlash('success', 'Vielen Dank für deine Anmeldung!');
                return $this->redirectToRoute('volunteer_enroll_thankyou');
            }
            else {
                $this->addFlash('error', 'Anmeldung nicht erfolgreich. Das Mail konnte nicht versendet werden');  
            }
        }

        return $this->render('volunteer/enroll.html.twig', [
            'enrollForm' => $form->createView(),
            'missions' => $missions
        ]);
    }

    /**
     * @Route("/volunteer/enroll/thankyou", name="volunteer_enroll_thankyou")
     */
    public function enroll_thankyou()
    {
        return $this->render('volunteer/enroll.thankyou.html.twig');
    }

}
