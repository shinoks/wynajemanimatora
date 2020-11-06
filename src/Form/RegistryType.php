<?php
namespace App\Form;

use App\Entity\RegistryCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RegistryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options):void
    {
        $builder
            ->add('name',TextType::class,['label'=>'registry.name','required' => true,'attr'=>['class'=>'form-control']])
            ->add('city',TextType::class,['label'=>'registry.city','required' => true,'attr'=>['class'=>'form-control']])
            ->add('zipCode',TextType::class,['label'=>'registry.zipCode','required' => false,'attr'=>['class'=>'form-control']])
            ->add('email',TextType::class,['label'=>'email','required' => false,'attr'=>['class'=>'form-control']])
            ->add('phone',TextType::class,['label'=>'phone','required' => false,'attr'=>['class'=>'form-control']])
            ->add('description',TextareaType::class,['label'=>'registry.description','required' => true,'attr'=>['class'=>'form-control']])
            ->add('registryCategories',EntityType::class,[
                'class' => RegistryCategory::class,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => 'name',
                'label'=>'registry.categories',
            ])

        ->add('save', SubmitType::class, [
            'label' => 'register',
            'attr'=>[
                'class'=>'btn btn-primary btn-block'
            ]
        ]);
    }
}
