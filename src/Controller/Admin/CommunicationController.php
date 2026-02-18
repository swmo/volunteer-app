<?php

namespace App\Controller\Admin;

use App\Form\Admin\CommunicationTemplateFormType;
use App\Manager\CommunicationTemplateManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class CommunicationController extends AbstractController
{
    #[Route('/communication/registration-template', name: 'admin_communication_registration_template')]
    public function registrationTemplate(
        Request $request,
        EntityManagerInterface $em,
        CommunicationTemplateManager $communicationTemplateManager
    ) {
        $template = $communicationTemplateManager->getOrCreateRegistrationTemplate();

        $form = $this->createForm(CommunicationTemplateFormType::class, $template);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $template->setUpdatedAt(new \DateTimeImmutable());
            $em->persist($template);
            $em->flush();

            $this->addFlash('success', 'Kommunikations-Template wurde gespeichert');

            return $this->redirectToRoute('admin_communication_registration_template');
        }

        return $this->render('admin/communication/registration_template.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

