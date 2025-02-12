<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CsrfTokenType;

class UserType extends AbstractType
{
    
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

                ->add('matricule', TextType::class, [
                    'required' => false, 
                    'empty_data' => '', 
                ])
                ->add('firstName', TextType::class, [
                'required' => false,
                'empty_data' =>'',
                'label' => 'Prénom',
                'attr' => [
                'placeholder' => 'Votre prénom'
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                'placeholder' => 'Votre nom'
                ]
            ])
            ->add('birthday', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'empty_data' => null,
                'label' => 'Date de naissance'
                ])
                ->add('telephone', TelType::class, [
                    'label' => 'Téléphone',
                    'attr' => [
                        'placeholder' => 'Votre téléphone pro'
                    ]
                ])
                ->add('service', TextType::class, [
                    'label' => 'Service',
                    'attr' => [
                        'placeholder' => 'Votre service d\'affectation'
                    ]
                ])
                ->add('speciality', TextType::class, [
                    'label' => 'Spécialité',
                    'attr' => [
                        'placeholder' => 'Votre spécialité'
                    ]
                ])
                ->add('photo', FileType::class, [
                    'label' => 'Photo de profil',
                    'required' => false, 
                    'data_class' => null, 
                ])
                                
                ->add('email', EmailType::class, [
                    'label' => 'Adresse éléctronique',
                    'attr' => [
                        'placeholder' => 'Votre adresse éléctronique valide'
                    ]
                ])
                ->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Le mot de passe et la confirmation doivent être identique',
                    'label' => 'Mot de passe',
                    'required' => true,
                    'first_options' => [
                        'label' => 'Mot de passe',
                        'attr' => [
                            'placeholder' => 'mot de passe de génie'
                        ]
                    ],
                    'second_options' => [
                        'label' => 'Confirmer mot de passe',
                        'attr' => [
                            'placeholder' => 'confirmer votre mot de passe de génie'
                        ]
                    ]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => true, // Activation du token CSRF
            'csrf_field_name' => '_token', // Nom du champ
            'csrf_token_id' => 'user_form',
        ]);
    }
}
