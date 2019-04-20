<?php

namespace App\Controller;

use App\Form\VolunteerFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Enrollment;
use App\Entity\Mission;

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
           
            $message = (new \Swift_Message('Hello Email'))
                ->setFrom('moses.tschanz@gmail.com')
                ->setTo('moses.tschanz@gmail.com')
                ->setBody(
                    $this->renderView(
                        // templates/emails/registration.html.twig
                        'emails/registration.html.twig',
                        ['enrollment' => $enrollment]
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
                $this->addFlash('success', 'Vielen Dank für deine Anmeldung!');
                return $this->redirectToRoute('volunteer_enroll');
            }
            else {
                $this->addFlash('success', 'Anmeldung nicht erfolgreich. Das Mail konnte nicht versendet werden');  
            }
        }

        return $this->render('volunteer/enroll.html.twig', [
            'enrollForm' => $form->createView(),
            'missions' => $missions
        ]);
    }
}
