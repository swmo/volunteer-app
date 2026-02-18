<?php

namespace App\Form\Admin;

use App\Entity\CommunicationTemplate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommunicationTemplateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject', null, ['label' => 'Betreff'])
            ->add('body', TextareaType::class, [
                'label' => 'Template (Twig/HTML)',
                'attr' => [
                    'rows' => 24,
                    'style' => 'font-family: monospace;',
                ],
            ])
            ->add('save', SubmitType::class, ['label' => 'Speichern'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CommunicationTemplate::class,
        ]);
    }
}

