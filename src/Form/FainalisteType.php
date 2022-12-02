<?php

namespace App\Form;

use App\Entity\Fainaliste;
use App\Entity\Finale;
use App\Entity\Scrutin;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class FainalisteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,['attr'=>['class'=>'form-control', 'autocomplete'=>'off']])
            ->add('media', FileType::class,[
                'attr'=>['class'=>"dropify", 'data-preview' => ".preview"],
                'label' => "Télécharger la photo de famille",
                'mapped' => false,
                'multiple' => false,
                'constraints' => [
                    new File([
                        'maxSize' => "10240k",
                        'mimeTypes' =>[
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                            'image/gif',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => "Votre fichier doit être de type image"
                    ])
                ],
                'required' => false
            ])
            //->add('slug')
            ->add('commune', null, ['attr'=>['class'=>'form-control select2']])
            ->add('finale', EntityType::class,[
                'attr' => ['class'=>'form-control select2'],
                'class' => Finale::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('f')
                        //->where('f.debut >= :date')
                        //->andWhere('f.fin <= :date')
                        ->orderBy('f.fin', 'DESC')
                        //->setParameter('date', date('Y-m-d'))
                        ;
                },
                'choice_label' => 'nom'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fainaliste::class,
        ]);
    }
}
