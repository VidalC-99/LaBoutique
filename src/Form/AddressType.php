<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label'=> 'Nom de votre adresse',
                'attr' => [
                    'placeholder' => 'Nommez votre adresse'
                ]
            ])
            ->add('firstname', TextType::class, [
                'label'=> 'Votre prénom',
                'attr' => [
                    'placeholder' => 'Saisisez votre présnom'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label'=> 'Votre nom',
                'attr' => [
                    'placeholder' => 'Saisissez votre nom'
                ]
            ])
            ->add('company', TextType::class, [
                'label'=> 'Votre société',
                'required' => false,
                'attr' => [
                    'placeholder' => '(Facultatif) nom de votre sociéte'
                ]
            ])
            ->add('address', TextType::class, [
                'label'=> 'Votre adresse',
                'attr' => [
                    'placeholder' => 'Votre adresse (Exemple : 7 rue de Paris..)'
                ]
            ])
            ->add('postal', TextType::class, [
                'label'=> 'Votre code postal',
                'attr' => [
                    'placeholder' => 'Saisissez votre code postal'
                ]
            ])
            ->add('City', TextType::class, [
                'label'=> 'Votre ville',
                'attr' => [
                    'placeholder' => 'Saisissez votre ville'
                ]
            ])
            ->add('Country', CountryType::class, [
                'label'=> 'Votre pays',
                'attr' => [
                    'placeholder' => 'nommer votre adresse'
                ]
            ])
            ->add('phone', TextType::class, [
                'label'=> 'Votre numéro de téléphone',
                'attr' => [
                    'placeholder' => 'Saisissez votre numéro de téléphone'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class'=> 'btn btn-block btn-info'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
