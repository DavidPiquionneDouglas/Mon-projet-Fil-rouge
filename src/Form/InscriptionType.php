<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('matricule', TextType::class, [
                'required'   => false,
                'empty_data' => '',
            ])
            ->add('firstName', TextType::class, [
                'required'   => false,
                'empty_data' => '',
                'label'      => 'Prénom',
                'attr'       => ['placeholder' => 'Votre prénom']
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'attr'  => ['placeholder' => 'Votre nom']
            ])
            ->add('birthday', DateType::class, [
                'widget'     => 'single_text',
                'required'   => false,
                'empty_data' => null,
                'label'      => 'Date de naissance'
            ])
            ->add('telephone', TelType::class, [
                'label' => 'Téléphone',
                'attr'  => ['placeholder' => 'Votre téléphone pro']
            ])
            ->add('service', TextType::class, [
                'label' => 'Service',
                'attr'  => ['placeholder' => 'Votre service d\'affectation']
            ])
            ->add('speciality', TextType::class, [
                'label' => 'Spécialité',
                'attr'  => ['placeholder' => 'Votre spécialité']
            ])
            ->add('photo', FileType::class, [
                'label'     => 'Photo de profil',
                'required'  => false,
                'data_class'=> null,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse électronique',
                'attr'  => ['placeholder' => 'Votre adresse électronique valide']
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr'  => ['placeholder' => 'Mot de passe sécurisé']
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label'    => 'Confirmer le mot de passe',
                'mapped'   => false, 
                'attr'     => ['placeholder' => 'Répétez le mot de passe'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez confirmer votre mot de passe.'])
                ]
            ])
        ;
        
        $builder->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) {
            $form = $event->getForm();
            $password = $form->get('password')->getData();
            $confirm  = $form->get('confirmPassword')->getData();
            if ($password !== $confirm) {
                $form->get('confirmPassword')->addError(new FormError('Les mots de passe ne correspondent pas.'));
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'new_user',
        ]);
    }
}
