<?php

namespace App\Form;

use App\Entity\VariationOption;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $couleurs = $options['couleurs'];
        $colors = [];
        foreach ($couleurs as $key => $value) {
            $colors[] = [
                 $value => $key 
            ];
        }
        
        $builder
            ->add('colors',ChoiceType::class,[
                'label' => 'Couleur',
                'required' => true,
                'choices' => $colors,
                'multiple' => false,
                'expanded' => false
            ])

            ->add('quantity',IntegerType::class,[
                'required' => true,
                'label' => 'Quantite',
                'attr' => [
                    'min' => 1,
                    'max' => 10
                ]
            ])

            ->add('submit',SubmitType::class,[
                'label' => "Ajouter au panier"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'couleurs' => array()
        ]);
    }
}
