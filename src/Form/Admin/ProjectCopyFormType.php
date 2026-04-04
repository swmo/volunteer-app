<?php

namespace App\Form\Admin;

use App\Entity\Organisation;
use App\Entity\Project;
use App\Repository\ProjectRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectCopyFormType extends AbstractType
{
    private ProjectRepository $projectRepository;

    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $organisation = $options['organisation'];
        $projects = $organisation instanceof Organisation
            ? $this->projectRepository->findBy(['organisation' => $organisation], ['name' => 'ASC'])
            : [];

        $builder
            ->add('name', null, ['label' => 'admin.form.project_copy.name'])
            ->add('missionsDate', DateType::class, [
                'label' => 'admin.form.project_copy.missions_date',
                'widget' => 'single_text',
            ])
            ->add('project',EntityType::class,[
                'label' => 'admin.form.project_copy.template_project',
                'class' => Project::class,
                'placeholder' => 'admin.form.project_copy.choose_project',
                'choice_label' => function(Project $project){
                    return $project->getName();
                },
                'choices' => $projects
            ])
            ->add('save', SubmitType::class, ['label' => 'admin.form.save'])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'messages',
            'organisation' => null,
        ]);
    }
}
