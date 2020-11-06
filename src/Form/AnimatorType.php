<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AnimatorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options):void
    {
        $builder
            ->add('email',TextType::class,['label'=>'email','required' => true,'attr'=>['class'=>'form-control']])
            ->add('password',PasswordType::class,['label'=>'password.password','required' => true,'attr'=>['class'=>'form-control']])
            ->add('regulations',CheckboxType::class, ['label'=>'regulations','required' => true ,'attr'=>['class'=>'form-control']])
            ->add('regulationFromRegister',CheckboxType::class, [
                'label'=>'Wyrażam zgodę na przetwarzanie moich danych osobowych w rozumieniu Rozporządzenia Parlamentu Europejskiego i Rady (UE) 2016/679 z dnia 27 kwietnia 2016 r. w sprawie ochrony osób fizycznych w związku z przetwarzaniem danych osobowych i w sprawie swobodnego przepływu takich danych oraz uchylenia dyrektywy 95/46/WE (ogólne rozporządzenie o ochronie danych), oraz ustawy z dnia 16 lipca 2004 roku Prawo telekomunikacyjne w celach założenia konta przez Koriko 43-227 Gilowice, ul. Wiejska 9 i oświadczam, iż podanie przeze mnie danych osobowych jest dobrowolne oraz iż zostałem poinformowany o prawie żądania dostępu do moich danych osobowych, ich zmiany, usunięcia, oraz wycofania zgody w dowolnym momencie.',
                'required' => true ,
                'attr'=>[
                    'class'=>'form-control',
                    'style'=>'font-size:8px'
                ]
            ])
            ->add('marketingRegulations',CheckboxType::class, [
                'label'=>'Wyrażam zgodę na przetwarzanie moich danych osobowych w rozumieniu Rozporządzenia Parlamentu Europejskiego i Rady (UE) 2016/679 z dnia 27 kwietnia 2016 r. w sprawie ochrony osób fizycznych w związku z przetwarzaniem danych osobowych i w sprawie swobodnego przepływu takich danych oraz uchylenia dyrektywy 95/46/WE (ogólne rozporządzenie o ochronie danych), oraz ustawy z dnia 16 lipca 2004 roku Prawo telekomunikacyjne w celach marketingowych przez Koriko 43-227 Gilowice, ul. Wiejska 9 i oświadczam, iż podanie przeze mnie danych osobowych jest dobrowolne oraz iż zostałem poinformowany o prawie żądania dostępu do moich danych osobowych, ich zmiany, usunięcia, oraz wycofania zgody w dowolnym momencie.',
                'required' => false ,
                'attr'=>[
                    'class'=>'form-control',
                    'style'=>'font-size:8px'
                ]
            ])

        ->add('save', SubmitType::class, [
            'label' => 'register',
            'attr'=>[
                'class'=>'btn btn-primary btn-block'
            ]
        ]);
    }
}
