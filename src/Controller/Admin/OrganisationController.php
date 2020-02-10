<?php

namespace App\Controller\Admin;

use App\Entity\Organisation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin")
 */
class OrganisationController extends AbstractController
{
    /**
     * @Route("/organisation/list", name="admin_organisation_list")
     */
    public function list(EntityManagerInterface $em)
    {

        $organisations = $em->getRepository(Organisation::class)->findAll();
        
        return $this->render('admin/organisation/list.html.twig', [
            'organisations' => $organisations,
        ]);
    }
}
