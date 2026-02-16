<?php

namespace App\Form\Admin;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
        $builder
            ->add('name', null, ['label' => 'admin.form.project_copy.name'])
            ->add('project',EntityType::class,[
                'label' => 'admin.form.project_copy.template_project',
                'class' => Project::class,
                'placeholder' => 'admin.form.project_copy.choose_project',
                'choice_label' => function(Project $project){
                    return $project->getName();
                },
                'choices' => $this->projectRepository->findAll()
            ])
            ->add('save', SubmitType::class, ['label' => 'admin.form.save'])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'messages',
        ]);
    }
}
