<?php

namespace App\Form\Admin;

use App\Entity\Mission;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Image;

class MissionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isEnabled', null, ['label' => 'admin.form.mission.enabled'])
            ->add('name', null, ['label' => 'admin.form.mission.name'])
            ->add('shortDescription', null, ['label' => 'admin.form.mission.short_description'])
            ->add('image', null, ['label' => 'admin.form.mission.image'])
            ->add('imageFile', FileType::class, [
                'label' => 'admin.form.mission.image_upload',
                'mapped' => false,
                'required' => false,
                'help' => 'admin.form.mission.image_upload_help',
                'constraints' => [
                    new Image(
                        maxSize: '5M'
                    ),
                ],
            ])
            ->add('start', null, ['label' => 'admin.form.mission.start'])
            ->add('end', null, ['label' => 'admin.form.mission.end'])
            ->add('requiredVolunteers', null, ['label' => 'admin.form.mission.required_volunteers'])
            ->add('project', null, ['label' => 'admin.form.mission.project'])
            ->add('meetingPoint', null, ['label' => 'admin.form.mission.meeting_point'])
            ->add('calendarEventDescription', null, ['label' => 'admin.form.mission.calendar_event_description'])
            ->add('save', SubmitType::class, ['label' => 'admin.form.save'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Mission::class,
            'translation_domain' => 'messages',
        ]);
    }
}
