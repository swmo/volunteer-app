<?php

namespace App\Controller\Admin;

use App\Entity\Organisation;
use App\Entity\User;
use App\Manager\UserOrganisationManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/admin")
 */
class OrganisationController extends AbstractController
{
    /**
     * @Route("/organisation/list", name="admin_organisation_list")
     */
    public function list(EntityManagerInterface $em, UserOrganisationManager $userOrganisationManager)
    {

        $organisations = $em->getRepository(Organisation::class)->findAll();
        
        return $this->render('admin/organisation/list.html.twig', [
            'organisations' => $organisations,
        ]);
    }


    /**
     * @Route("/organisation/select/{id}", name="admin_organisation_select")
     */
    public function select(Organisation $organisation, EntityManagerInterface $em, Security $security){

        $user = $security->getUser();
       
        $user->setSelectedOrganisation($organisation);
        $em->persist($user);
        $em->flush();
        
        return $this->redirectToRoute('admin_project_list');

    }

}
