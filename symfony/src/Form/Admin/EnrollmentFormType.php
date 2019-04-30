<?php

namespace App\Form\Admin;

use App\Entity\Enrollment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnrollmentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('street')
            ->add('zip')
            ->add('city')
            ->add('mobile')
            ->add('email')
            ->add('birthday')
            ->add('tshirtsize')
            ->add('comment')
            ->add('hasTshirt')
            ->add('confirmToken')
            ->add('status')
            ->add('missionChoice01')
            ->add('missionChoice02')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Enrollment::class,
        ]);
    }
}
