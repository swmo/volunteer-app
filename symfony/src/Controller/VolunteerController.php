<?php

namespace App\Controller;

use App\Form\VolunteerFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VolunteerController extends AbstractController
{

    /**
     * @Route("/volunteer/enroll", name="volunteer_enroll")
     */
    public function register()
    {
        $form = $this->createForm(VolunteerFormType::class);
        return $this->render('volunteer/register.html.twig', [
            'enrollForm' => $form->createView()
        ]);
    }

}
