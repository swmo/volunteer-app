<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class VolunteerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //parent::buildForm($builder,$options);
        $builder
        ->add('firstname')
        ->add('lastname')
    ;
    }


}
