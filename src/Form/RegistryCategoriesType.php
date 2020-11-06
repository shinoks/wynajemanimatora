<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RegistryCategoriesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options):void
    {
        $builder
            ->add('name',TextType::class,['label'=>'categories.name','required' => true,'attr'=>['class'=>'form-control']])
            ->add('enabled',CheckboxType::class,['label'=>'enabled','required' => false,'attr'=>['class'=>'form-control']])

        ->add('save', SubmitType::class, [
            'label' => 'submit',
            'attr'=>[
                'class'=>'btn btn-primary btn-block'
            ]
        ]);
    }
}
