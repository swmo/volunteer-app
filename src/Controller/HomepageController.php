<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\OrganisationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /** 
    * @Route("/", name="home")
     */
    public function home(Request $request, EntityManagerInterface $em,OrganisationRepository $organisationRepository){

        // read the host / domainname which the user open so the system knows which project he has to load
        // example helfer.burgdorfer-stadtlauf.ch
        $host = $request->headers->get('host');
        $project = $em->getRepository(Project::class)->findOneBy(['isEnabled' => true,'domain' => $host]);
        if($project){
            return $this->redirectToRoute('volunteer_enroll_by_project',array('id'=>$project->getId()));
        }

        // if i couldnt found a project show the list of all organisations
        $organisations = $organisationRepository->findAll();

         return $this->render('homepage/organisations.html.twig', [
             'organisations' => $organisations
         ]);
    }
}
