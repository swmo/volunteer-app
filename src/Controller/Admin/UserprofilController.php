<?php

namespace App\Controller\Admin;

use App\Form\Admin\ChangePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class UserprofilController extends AbstractController
{
    /**
     * @Route("/userprofil", name="admin_userprofil")
     */
    public function index(Request $request, EntityManagerInterface $em)
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordFormType::class,$user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $em->persist($user);
            $em->flush();       

            $this->addFlash(
                'success',
                'Passwort wurde gespeichert'
            );

            return $this->redirectToRoute('admin_userprofil');
        }

        return $this->render('admin/userprofil/index.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
