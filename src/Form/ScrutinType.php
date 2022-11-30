<?php

namespace App\Form;

use App\Entity\Scrutin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ScrutinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,['attr'=>['class'=>'form-control', 'autocomplete'=>'off']])
            ->add('date', DateType::class,[
                'attr'=>['class'=>'form-control js-datepicker', 'autocomplete'=>"off"],
                'widget' => 'single_text',
                'html5' => false
            ])
            ->add('statut', CheckboxType::class,['attr'=>['class'=>'form-check-input'], 'required' => false])
            //->add('slug')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Scrutin::class,
        ]);
    }
}
