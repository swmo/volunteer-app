<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use App\Entity\Enrollment;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Mission;

class VolunteerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //parent::buildForm($builder,$options);
        $builder
        ->add('firstname')
        ->add('lastname')
        ->add('street')
        ->add('zip')
        ->add('city')
        ->add('mobile')
        ->add('email')
        ->add('missionChoice01', EntityType::class, [
            'label' => 'gewünschten Einsatzort / Wahl 1:',
            'class'  => Mission::class,
            'choice_label' => 'name',
            'placeholder' => 'Einsatzort spielt  mir keine Rolle',
            'required' => false,
        ])
        ->add('missionChoice02', EntityType::class, [
            'label' => 'gewünschten Einsatzort / Wahl 2:',
            'class'  => Mission::class,
            'choice_label' => 'name',
            'placeholder' => 'Einsatzort spielt  mir keine Rolle',
            'required' => false,
        ])
        ->add('birthday', DateType::class, [
            'widget' => 'choice',
            'label' => 'Geburtstag',
        ])
        ->add('tshirtsize', TextType::class, [
            'label' => 'T-Shirt Grösse'
        ])
    ;
    }

    public function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Enrollment::class
        ]);
    }

 
}
