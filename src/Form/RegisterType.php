<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('pseudoname',TextType::class,[
            'label' => 'Votre PseudoName :',
            'required' => true,


        ])
        ->add('firstname', TextType::class, [
                'label' => 'Votre Prénom :',
                'required' => true,
                
        ])
        ->add('email',EmailType::class,[
            'label' => 'Votre Email :',
            'required' => true,
        ])
        ->add('password', RepeatedType::class,[
            'type' => PasswordType::class,
            'invalid_message' => 'Les mot de passes sont different !',
            'required' => true,
            'first_name' => 'pass',
            'second_name' => 'confirm',
            'first_options' => ['label' => 'Votre mot de passe :'],
            'second_options' => ['label' => 'Confirmez votre mot de passe :']

        ])
        ->add('submit',SubmitType::class,[
            'label' => "S'inscrire"
        ])
            
            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
