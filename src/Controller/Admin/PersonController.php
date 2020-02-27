<?php

namespace App\Controller\Admin;

use App\Entity\Person;
use App\Manager\UserOrganisationManager;
use App\Repository\PersonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
}
