<?php

namespace App\Form;

use App\Entity\Finale;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FinaleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,['attr'=>['class'=>'form-control', 'autocomplete'=>'off']])
            ->add('debut', DateType::class,[
                'attr'=>['class'=>'form-control js-datepicker', 'autocomplete'=>"off"],
                'widget' => 'single_text',
                'html5' => false
            ])
            ->add('fin', DateType::class,[
                'attr'=>['class'=>'form-control js-datepicker2', 'autocomplete'=>"off"],
                'widget' => 'single_text',
                'html5' => false
            ])
            ->add('statut', CheckboxType::class,['attr'=>['class'=>'form-check-input'], 'required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Finale::class,
        ]);
    }
}
