<?php

namespace App\Controller;

use App\Controller\Admin\OrganisationController;
use App\Form\VolunteerFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Enrollment;
use App\Entity\Mission;
use Symfony\Component\Filesystem\Filesystem;
use App\Utils\TokenGeneratorInterface;
use Symfony\Component\Workflow\Registry;
use App\Entity\Person;
use App\Entity\Project;
use App\Manager\ProjectManager;
use App\Repository\OrganisationRepository;
use App\Utils\IcsGenerator;
use App\Utils\MergePerson;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Response;

class VolunteerController extends AbstractController
{

    #[Route("/volunteer/enroll/project/{id}", name: "volunteer_enroll_by_project")]

    public function enrollByProject(EntityManagerInterface $em, Request $request, MailerInterface $mailer, TokenGeneratorInterface $tokenGenerator, Registry $workflows, IcsGenerator $icsGenerator, MergePerson $mergePerson, ?Project $project = null)
    {

        if($project === null){
            return $this->redirectToRoute('home');
        }
        // If some saved the link as Lesezeichen and the project is not active anymore
        if(!$project->isEnabled()){
            return $this->redirectToRoute('home');
        }

        $enrollmentSettings = $project->getEnrollmentSettings();
        if (
            !is_array($enrollmentSettings) ||
            !isset($enrollmentSettings['form']) ||
            !is_array($enrollmentSettings['form'])
        ) {
            return $this->render(
                'volunteer/enroll.unavailable.html.twig',
                ['project' => $project],
                new Response('', Response::HTTP_SERVICE_UNAVAILABLE)
            );
        }

        $form = $this->createForm(VolunteerFormType::class, null, array(
            'project' => $project,
        ));

        $missions = $em->getRepository(Mission::class)->findBy(array('isEnabled' => true, 'project' => $project), array('name' => 'ASC'));;
        $projectTotalRequiredPersons = 0;
        foreach ($missions as $mission) {
            $projectTotalRequiredPersons += (int) $mission->getRequiredVolunteers();
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        
            $enrollment = $form->getData();
            /** @var Enrollment $enrollment */
            $enrollment->setConfirmToken($tokenGenerator->generateToken());
            //temp fix:
            $enrollment->setProject($project);
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

            // Keep person master data in sync even if doctrine subscriber is not triggered.
            $mergePerson->mergeEnrollemnt($enrollment);

            
            $message = (new Email())
                ->subject('Anmeldung | Burgdorfer Stadtlauf')
                ->from('personal@burgdorfer-stadtlauf.ch')
                ->to($enrollment->getEmail())
                ->bcc('personal@burgdorfer-stadtlauf.ch')
                ->html(
                    $this->renderView(
                        'emails/registration.html.twig',
                        [
                            'enrollment' => $enrollment,
                            'projectManager' => new ProjectManager($project)
                        ]
                    )
                );

            if($enrollment->getMissionChoice01()){
                $message->attachFromPath(
                    $icsGenerator->generateMissionIcs($enrollment->getMissionChoice01()),
                    $enrollment->getMissionChoice01()->getName().'.ics',
                    'text/calendar; charset=UTF-8; method=REQUEST'
                );
            }

            if($enrollment->getMissionChoice02()){
                $message->attachFromPath(
                    $icsGenerator->generateMissionIcs($enrollment->getMissionChoice02()),
                    $enrollment->getMissionChoice02()->getName().'.ics',
                    'text/calendar; charset=UTF-8; method=REQUEST'
                );
            }

            try {
                $mailer->send($message);
                return $this->redirectToRoute('volunteer_enroll_thankyou',['project'=>$enrollment->getProject()->getId()]);
            } catch (TransportExceptionInterface $exception) {
                $this->addFlash('error', 'Anmeldung nicht erfolgreich. Das Mail konnte nicht versendet werden');  
            }
        }

        return $this->render('volunteer/enroll.html.twig', [
            'enrollForm' => $form->createView(),
            'missions' => $missions,
            'project' => $project,
            'projectEnrolledPersonsCount' => $this->countActiveProjectEnrollments($project),
            'projectTotalRequiredPersons' => $projectTotalRequiredPersons,
        ]);
    }

    #[Route("/volunteer/enroll/thankyou/project/{project}", name: "volunteer_enroll_thankyou")]

    public function enroll_thankyou(Project $project)
    {
        return $this->render('volunteer/enroll.thankyou.html.twig',[
            'project' => $project
        ]);
    }

    #[Route("/volunteer/enroll/confirm/email/{id}/{token}", name: "volunteer_confirm_enrollment_email")]

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


    private function countActiveProjectEnrollments(Project $project): int
    {
        $count = 0;

        foreach ($project->getEnrollments() as $enrollment) {
            if (!in_array('deleted', $enrollment->getStatus() ?? [], true)) {
                $count++;
            }
        }

        return $count;
    }

}
