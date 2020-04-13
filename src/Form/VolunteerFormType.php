<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use App\Entity\Enrollment;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Mission;
use App\Entity\Project;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use App\Repository\MissionRepository;

class VolunteerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $project = $options['project'];

        //parent::buildForm($builder,$options);
        $builder
        ->add('firstname',TextType::class,[
            'label' => 'firstname'
        ])
        ->add('lastname',TextType::class,[
            'label' => 'lastname'
        ])
        ->add('street',TextType::class,[
            'label' => 'street'
        ])
        ->add('zip',TextType::class,[
            'label' => 'zip'
        ])
        ->add('city',TextType::class,[
            'label' => 'city'
        ])
        ->add('mobile',TextType::class,[
            'label' => 'mobile'
        ])
        ->add('email',EmailType::class,[
            'label' => 'email'
        ])
        ->add('missionChoice01', EntityType::class, [
            'label' => 'gewünschten Einsatzort/e',
            'class'  => Mission::class,
            'choice_label' => 'getNameForSelectbox',
            'required' => true,
            'placeholder' => 'Bitte Einsatz wählen', 
            'query_builder' => function(MissionRepository $repo) use ($project) {
                return $repo->createQueryBuilder('m')
                ->andWhere('m.isEnabled = true')
                ->andWhere('m.project = '.$project->getId())
                ->orderBy('m.name', 'ASC');
            }
        ])
        ->add('missionChoice02', EntityType::class, [
            'label' => '',
            'class'  => Mission::class,
            'choice_label' => 'getNameForSelectbox',
            'placeholder' => 'kein zweiter Einsatz gewünscht',
            'required' => false,   
            'query_builder' => function(MissionRepository $repo) use ($project)  {
                return $repo->createQueryBuilder('m')
                ->andWhere('m.isEnabled = true')
                ->andWhere('m.project = '.$project->getId())
                ->orderBy('m.name', 'ASC');
            }
        ])
        ->add('birthday', DateType::class, [
            'widget' => 'single_text',
            'label' => 'birthday',
            'format' => 'dd.MM.yyyy',
            // prevents rendering it as type="date", to avoid HTML5 date pickers
            'html5' => false,
            // adds a class that can be selected in JavaScript
            'attr' => ['class' => 'js-datepicker'],
        ])
        ->add('hasTshirt', ChoiceType::class, [
            'label' => 'Helfer T-Shirt vom letzten Jahr vorhanden und kann mitgenommen werden',
            'choices'  => [
                'Ja' => true,
                'Nein' => false,
            ],
            'placeholder' => 'Bitte auswählen',
            'required' => true,
        ])
        ->add('tshirtsize', ChoiceType::class, [
            'label' => 'Deine T-Shirt Grösse',
            'choices'  => [
                'S' => 'S',
                'M' => 'M',
                'L' => 'L',
                'XL' => 'XL',
            ],
            'placeholder' => 'Bitte grösse wählen',
            'required' => true,
        ])
        ->add('comment', TextareaType::class, [
            'label' => 'Bemerkungen / Anregungen / Wünsche',
            'required' => false,
        ])
    ;
    }

    public function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Enrollment::class,
            'translation_domain', 'messages'
        ]);

        $resolver->setRequired('project');
        // type validation - project instance
        $resolver->setAllowedTypes('project', array(Project::class));
    }

 
}
