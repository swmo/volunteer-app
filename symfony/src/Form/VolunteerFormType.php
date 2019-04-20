<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use App\Entity\Enrollment;

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
        ->add('missionChoice01')
        ->add('missionChoice02')
        ->add('birthday',DateType::class, [
            'widget' => 'choice',
        ])
        ->add('tshirtsize')
    ;
    }

    public function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Enrollment::class
        ]);
    }

 
}
