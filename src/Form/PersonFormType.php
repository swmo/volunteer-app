<?php

namespace App\Form;

use App\Entity\Person;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', null, ['label' => 'admin.form.person.lastname'])
            ->add('firstname', null, ['label' => 'admin.form.person.firstname'])
            ->add('street', null, ['label' => 'admin.form.person.street'])
            ->add('city', null, ['label' => 'admin.form.person.city'])
            ->add('zip', null, ['label' => 'admin.form.person.zip'])
            ->add('mobile', null, ['label' => 'admin.form.person.mobile'])
            ->add('email', null, ['label' => 'admin.form.person.email'])
            ->add('remark', null, ['label' => 'admin.form.person.remark'])
            ->add('organisations', null, ['label' => 'admin.form.person.organisations'])
            ->add('save', SubmitType::class, ['label' => 'admin.form.save'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Person::class,
            'translation_domain' => 'messages',
        ]);
    }
}
