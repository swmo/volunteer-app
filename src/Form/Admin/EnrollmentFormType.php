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
        $enrollment = $builder->getData();
        $project = $enrollment instanceof Enrollment ? $enrollment->getProject() : null;

        $builder
            ->add('firstname', null, ['label' => 'admin.form.enrollment.firstname'])
            ->add('lastname', null, ['label' => 'admin.form.enrollment.lastname'])
            ->add('street', null, ['label' => 'admin.form.enrollment.street'])
            ->add('zip', null, ['label' => 'admin.form.enrollment.zip'])
            ->add('city', null, ['label' => 'admin.form.enrollment.city'])
            ->add('mobile', null, ['label' => 'admin.form.enrollment.mobile'])
            ->add('email', null, ['label' => 'admin.form.enrollment.email'])
            ->add(
                'birthday', DateType::class, [
                    'widget'    => 'single_text',
                    'label'     => 'admin.form.enrollment.birthday',
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
            ->add('tshirtsize', null, ['label' => 'admin.form.enrollment.tshirtsize'])
            ->add('comment', null, ['label' => 'admin.form.enrollment.comment'])
            ->add('hasTshirt', null, ['label' => 'admin.form.enrollment.has_tshirt'])
            ->add('confirmToken', null, ['label' => 'admin.form.enrollment.confirm_token'])
       //     ->add('status', TextType::class)
           // ->add('missionChoice01')


            ->add('missionChoice01', EntityType::class, [
                'label' => 'admin.form.enrollment.mission_choice_01',
                'class' => Mission::class,
                'required'  => false,
                'placeholder' => 'admin.form.placeholder.none',
                'query_builder' => function(MissionRepository $repo) use ($project) {
                    $qb = $repo->createQueryBuilder('m')
                        ->orderBy('m.name', 'ASC');

                    if (null !== $project) {
                        $qb->andWhere('m.project = :project')
                            ->setParameter('project', $project);
                    }

                    return $qb;
                }
            ])

            ->add('organizedStartTimeMissionChoice01', 
                TimeType::class, 
                [
                    'input'  => 'datetime',
                    'widget' => 'single_text',
                    'label' => 'admin.form.enrollment.organized_start_01',
                    'required' => false
                ]
            )
            ->add('organizedEndTimeMissionChoice01', 
                TimeType::class, 
                [
                    'input'  => 'datetime',
                    'widget' => 'single_text',
                    'label' => 'admin.form.enrollment.organized_end_01',
                    'required' => false
                ]
            )
            ->add('organizedDescriptionMissionChoice01', null, ['label' => 'admin.form.enrollment.organized_description_01'])




            
            ->add('missionChoice02', EntityType::class, [
                'label' => 'admin.form.enrollment.mission_choice_02',
                'class' => Mission::class,
                'required'  => false,
                'placeholder' => 'admin.form.placeholder.none',
                'query_builder' => function(MissionRepository $repo) use ($project) {
                    $qb = $repo->createQueryBuilder('m')
                        ->orderBy('m.name', 'ASC');

                    if (null !== $project) {
                        $qb->andWhere('m.project = :project')
                            ->setParameter('project', $project);
                    }

                    return $qb;
                }
            ])

            ->add('organizedStartTimeMissionChoice02', 
                TimeType::class, 
                [
                    'input'  => 'datetime',
                    'widget' => 'single_text',
                    'label' => 'admin.form.enrollment.organized_start_02',
                    'required' => false
                ]
            )
            ->add('organizedEndTimeMissionChoice02', 
                TimeType::class, 
                [
                    'input'  => 'datetime',
                    'widget' => 'single_text',
                    'label' => 'admin.form.enrollment.organized_end_02',
                    'required' => false
                ]
            )
            ->add('organizedDescriptionMissionChoice02', null, ['label' => 'admin.form.enrollment.organized_description_02'])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Enrollment::class,
            'translation_domain' => 'messages',
        ]);
    }
}
