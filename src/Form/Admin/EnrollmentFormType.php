<?php

namespace App\Form\Admin;

use App\Entity\Enrollment;
use App\Entity\Mission;
use App\Repository\MissionRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


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
            ->add(
                'birthday', DateType::class, [
                    'widget'    => 'single_text',
                    'label'     => 'birthday',
                    'format'    => 'dd.MM.yyyy',

                    // prevents rendering it as type="date", to avoid HTML5 date pickers
                    'html5'     => false,
                    // adds a class that can be selected in JavaScript
                    'attr'      => 
                    [
                        'class' => 'js-datepicker'
                    ],
                    'required' => false,
                ]
            )
            ->add('tshirtsize')
            ->add('comment')
            ->add('hasTshirt')
            ->add('confirmToken')
       //     ->add('status', TextType::class)
           // ->add('missionChoice01')


            ->add('missionChoice01', EntityType::class, [
                'label' => 'Einsatzort 1',
                'class' => Mission::class,
                'required'  => false,
                'placeholder' => '-- Keine Auswahl --',
                'query_builder' => function(MissionRepository $repo)  {
                    return $repo->createQueryBuilder('m')
                    ->orderBy('m.name', 'ASC');
                }
            ])

            ->add('organizedStartTimeMissionChoice01', 
                TimeType::class, 
                [
                    'input'  => 'datetime',
                    'widget' => 'single_text',
                    'required' => false
                ]
            )
            ->add('organizedEndTimeMissionChoice01', 
                TimeType::class, 
                [
                    'input'  => 'datetime',
                    'widget' => 'single_text',
                    'required' => false
                ]
            )
            ->add('organizedDescriptionMissionChoice01')




            
            ->add('missionChoice02', EntityType::class, [
                'label' => 'Einsatzort 2',
                'class' => Mission::class,
                'required'  => false,
                'placeholder' => '-- Keine Auswahl --',
                'query_builder' => function(MissionRepository $repo)  {
                    return $repo->createQueryBuilder('m')
                    ->orderBy('m.name', 'ASC');
                }
            ])

            ->add('organizedStartTimeMissionChoice02', 
                TimeType::class, 
                [
                    'input'  => 'datetime',
                    'widget' => 'single_text',
                    'required' => false
                ]
            )
            ->add('organizedEndTimeMissionChoice02', 
                TimeType::class, 
                [
                    'input'  => 'datetime',
                    'widget' => 'single_text',
                    'required' => false
                ]
            )
            ->add('organizedDescriptionMissionChoice02')

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Enrollment::class,
        ]);
    }
}
