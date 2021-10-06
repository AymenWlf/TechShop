<?php

namespace App\Form;

use App\Entity\Address;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\TextEditorType;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'required' => true
            ])
            ->add('firstname',TextType::class,[
                'required' => true
            ])
            ->add('lastname',TextType::class,[
                'required' => true
            ])
            ->add('company',TextType::class,[
                'required' => false
            ])
            ->add('address',TextType::class,[
                'required' => true
            ])
            ->add('postal',TextType::class,[
                'required' => true
            ])
            ->add('country',CountryType::class,[
                'required' => true
            ])
            ->add('city',TextType::class,[
                'required' => true
            ])
            ->add('phone',TelType::class,[
                'required' => true
            ])
            ->add('submit',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
