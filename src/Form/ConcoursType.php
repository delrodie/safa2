<?php

namespace App\Form;

use App\Entity\Concours;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConcoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,['attr'=>['class'=>'form-control', 'autocomplete'=>'off']])
            //->add('code')
            ->add('debut', DateType::class,[
                'attr'=>['class'=>'form-control js-datepicker'],
                'widget' => 'single_text',
                'html5' => false
            ])
            ->add('fin', DateType::class,[
                'attr'=>['class'=>'form-control js-datepicker'],
                'widget' => 'single_text',
                'html5' => false
            ])
            //->add('slug')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Concours::class,
        ]);
    }
}
