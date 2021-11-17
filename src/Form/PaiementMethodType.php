<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaiementMethodType extends AbstractType
{

    //NON UTILISER
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('CB',ChoiceType::class,[
                'label' => 'Carte Bancaire (facultatif)',
                'required' => true,
                'choices' => [
                    'CB' => 'Carte Bancaire (facultatif)',
                    'COD' => 'Cash à la livraison'
                ],
                'expanded' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
