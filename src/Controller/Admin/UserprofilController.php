<?php

namespace App\Controller\Admin;

use App\Form\Admin\ChangePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/admin")]

class UserprofilController extends AbstractController
{
    #[Route("/userprofil", name: "admin_userprofil")]

    public function index(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordFormType::class,$user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $plainPassword = $user->getPlainPassword();
            if (\is_string($plainPassword) && '' !== trim($plainPassword)) {
                $user->setPassword($passwordHasher->hashPassword($user, $plainPassword));
            } else {
                $this->addFlash('danger', 'Bitte ein gueltiges Passwort eingeben.');

                return $this->render('admin/userprofil/index.html.twig', [
                    'user' => $user,
                    'form' => $form->createView(),
                ]);
            }

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
