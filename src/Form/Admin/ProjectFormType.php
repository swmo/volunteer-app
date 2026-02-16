<?php

namespace App\Form\Admin;

use App\Entity\Project;
use App\Form\DataTransformer\ArrayToJSONStringTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectFormType extends AbstractType
{

    private $jsonTransformer;

    public function __construct(ArrayToJSONStringTransformer $jsonTransformer)
    {
        $this->jsonTransformer = $jsonTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'admin.form.project.name'])
            ->add('domain', null, ['label' => 'admin.form.project.domain'])
            ->add('isEnabled', null, ['label' => 'admin.form.project.enabled'])
            ->add('organisation', null, ['label' => 'admin.form.project.organisation'])
            ->add('enrollmentSettings',TextType::class,[
                'label' => 'admin.form.project.enrollment_settings',
                'help' => '
                {
                    "form":
                    {
                        "attributes": 
                        {
                            "lastname":true,
                            "street":true,
                            "zip":true,
                            "city":true,
                            "mobile":true,
                            "birthday":true,
                            "hasTshirt":true,
                            "tshirtsize":true,
                            "comment":true
                        }
                    }
                }
                    ',
            ])
            ->add('save', SubmitType::class, ['label' => 'admin.form.save'])
        ;

        $builder->get('enrollmentSettings')
            ->addModelTransformer($this->jsonTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
            'translation_domain' => 'messages',
        ]);
    }
}
