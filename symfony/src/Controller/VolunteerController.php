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
use App\Utils\TokenGeneratorInterface;
use Symfony\Component\Workflow\Registry;
use App\Entity\Person;

class VolunteerController extends AbstractController
{

    /**
     * @Route("/volunteer/enroll", name="volunteer_enroll")
     * @Route("/", name="home")
     */
    public function enroll(EntityManagerInterface $em, Request $request, \Swift_Mailer $mailer,TokenGeneratorInterface $tokenGenerator, Registry $workflows)
    {
        $form = $this->createForm(VolunteerFormType::class);
        $missions = $em->getRepository(Mission::class)->findBy(array(), array('name' => 'ASC'));;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        
            $enrollment = $form->getData();
            /** @var Enrollment $enrollment */
            $enrollment->setConfirmToken($tokenGenerator->generateToken());
            /*
            * Besteht die Person bereits unter den Stammdaten (Personen) so wird diese automatisch confirmed
            */
            /** @var Registry $workflows */
            if($em->getRepository(Person::class)->findOneBy(['email' => $enrollment->getEmail()])){
                $workflows->get($enrollment)->apply($enrollment,'confirm enrollment direct');
            }
            // todo: do a better compare (example: last character without spaces)
            elseif($em->getRepository(Person::class)->findOneBy(['mobile' => $enrollment->getMobile()])){
                $workflows->get($enrollment)->apply($enrollment,'confirm enrollment direct');
            }
            else{
                $workflows->get($enrollment)->apply($enrollment,'waiting for confirmations');
            }

            $em->persist($enrollment);
            $em->flush();           



            $fs = new Filesystem();

            //temporary folder, it has to be writable
            $tmpFolder = '/tmp/';

            //the name of your file to attach
            $fileName = 'Kalendereintrag_'.$enrollment->getId().'.ics';
//todo: trim??
        $icsContent = "BEGIN:VCALENDAR
PRODID:Burgdorfer Stadtlauf
VERSION:2.0
CALSCALE:GREGORIAN
BEGIN:VTIMEZONE
TZID:Europe/Zurich
BEGIN:DAYLIGHT
TZOFFSETFROM:+0100
TZOFFSETTO:+0200
TZNAME:CEST
END:DAYLIGHT
END:VTIMEZONE
";

$icsContent = $icsContent . "BEGIN:VEVENT
UID:".$enrollment->getMissionChoice01()->getId()."-".$enrollment->getId()."@helfer.burgdorfer-stadtlauf.ch
SUMMARY:Helfereinsatz Stadtlauf Burgdorf
DTSTAMP:".$enrollment->getMissionChoice01()->getStart()->format('Ymd\THis')."
DTSTART;TZID=Europe/Zurich:".$enrollment->getMissionChoice01()->getStart()->format('Ymd\THis')."
DTEND;TZID=Europe/Zurich:".$enrollment->getMissionChoice01()->getEnd()->format('Ymd\THis')."
LOCATION:".$enrollment->getMissionChoice01()->getMeetingPoint()."
DESCRIPTION:".$enrollment->getMissionChoice01()->getCalendarEventDescription()."
STATUS:CONFIRMED
SEQUENCE:0
END:VEVENT
";

if($enrollment->getMissionChoice02()){
    $icsContent = $icsContent . "BEGIN:VEVENT
UID:".$enrollment->getMissionChoice02()->getId()."-".$enrollment->getId()."@helfer.burgdorfer-stadtlauf.ch
SUMMARY:Helfereinsatz Stadtlauf Burgdorf
DTSTAMP:".$enrollment->getMissionChoice02()->getStart()->format('Ymd\THis')."
DTSTART;TZID=Europe/Zurich:".$enrollment->getMissionChoice02()->getStart()->format('Ymd\THis')."
DTEND;TZID=Europe/Zurich:".$enrollment->getMissionChoice02()->getEnd()->format('Ymd\THis')."
LOCATION:".$enrollment->getMissionChoice02()->getMeetingPoint()."
DESCRIPTION:".$enrollment->getMissionChoice02()->getCalendarEventDescription()."
STATUS:CONFIRMED
SEQUENCE:0
END:VEVENT
";      
}

$icsContent =  str_replace("\n","\r\n",$icsContent . "END:VCALENDAR");


            //creation of the file on the server
            $icfFile01 = $fs->dumpFile($tmpFolder.$fileName, $icsContent);

            $message = (new \Swift_Message('Anmeldung | Burgdorfer Stadtlauf'));

            $image = ($message->embed(\Swift_Image::fromPath('/var/www/public/images/maria2.jpg')));
           // $image = "";

            $message
                ->setFrom('personal@burgdorfer-stadtlauf.ch')
                ->setTo($enrollment->getEmail())
                ->setBcc('personal@burgdorfer-stadtlauf.ch')
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

            if($icfFile01){
                $message->attach(\Swift_Attachment::fromPath($tmpFolder.$fileName)->setContentType('text/calendar;charset=UTF-8;name="'.$fileName.'";method=REQUEST'));
            }

            if($mailer->send($message)){
                
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

    /**
     * @Route("/volunteer/enroll/confirm/email/{id}/{token}", name="volunteer_confirm_enrollment_email")
     */
    public function confirmEnrollmentEmail(Enrollment $enrollment, Request $request, Registry $workflows, EntityManagerInterface $em)
    {
        /** @var Request $request */
        /** @var Registry $workflows */
        if ($enrollment->getConfirmToken() === $request->get('token')){
            if($workflows->get($enrollment)->can($enrollment,'confirm email'))
            {
                $workflows->get($enrollment)->apply($enrollment,'confirm email');
                $em->persist($enrollment);
                $em->flush();    
                $this->addFlash('success', 'Super, danke wir konnten deine Mail Adresse bestätigen');      

            }
            else 
            {
                $this->addFlash('danger', 'E-Mail bereits bestätigt');  
            }
        }
        else 
        {
            $this->addFlash('danger', 'Ungültiges Token');  
        }
       
        return $this->redirectToRoute('home');
    }



}
