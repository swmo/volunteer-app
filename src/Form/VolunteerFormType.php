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
use App\Manager\ProjectManager;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use App\Repository\MissionRepository;

class VolunteerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Project */
        $project = $options['project'];

        $projectManager = new ProjectManager($project);
        
        $builder
        ->add('firstname',TextType::class,[
            'label' => 'firstname'
        ]);

        if($projectManager->getFormSetting('lastname')){
            $builder
            ->add('lastname',TextType::class,[
                'label' => 'lastname'
                ]);
        }
        if($projectManager->getFormSetting('street')){
            $builder
            ->add('street',TextType::class,[
                'label' => 'street'
            ]);
        }
        if($projectManager->getFormSetting('zip')){
            $builder
            ->add('zip',TextType::class,[
                'label' => 'zip'
            ]);
        }
        if($projectManager->getFormSetting('city')){
            $builder
            ->add('city',TextType::class,[
                'label' => 'city'
            ]);
        }
        if($projectManager->getFormSetting('mobile')){
            $builder
            ->add('mobile',TextType::class,[
                'label' => 'mobile'
            ]);
        }

        $builder
        ->add('email',EmailType::class,[
            'label' => 'email'
        ]);
        

        $builder
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
        ]);
     
        $builder
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
        ]);
        
        if($projectManager->getFormSetting('birthday')){
            $builder
            ->add('birthday', DateType::class, [
                'widget' => 'single_text',
                'label' => 'birthday',
                'format' => 'dd.MM.yyyy',
                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => false,
                // adds a class that can be selected in JavaScript
                'attr' => ['class' => 'js-datepicker'],
            ]);
        }
        if($projectManager->getFormSetting('hasTshirt')){
            $builder
            ->add('hasTshirt', ChoiceType::class, [
                'label' => 'Helfer T-Shirt vorhanden und kann mitgenommen werden',
                'choices'  => [
                    'Ja' => true,
                    'Nein' => false,
                ],
                'placeholder' => 'Bitte auswählen',
                'required' => true,
            ]);
        }
        if($projectManager->getFormSetting('tshirtsize')){
            $builder
            ->add('tshirtsize', ChoiceType::class, [
                'label' => 'Deine T-Shirt Grösse',
                'choices'  => [
                    'S' => 'S',
                    'M' => 'M',
                    'L' => 'L',
                    'XL' => 'XL',
                ],
                'placeholder' => 'Bitte Grösse wählen',
                'required' => true,
            ]);
        }
        if($projectManager->getFormSetting('comment')){
            $builder
            ->add('comment', TextareaType::class, [
                'label' => 'Bemerkungen / Anregungen / Wünsche',
                'required' => false,
            ]);
        }
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
