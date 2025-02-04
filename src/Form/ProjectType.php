<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle', TextType::class, [
                'label' => 'Libellé',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Nom du projet']
            ])
            ->add('code', TextType::class, [
                'label' => 'Code',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Code unique']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Détails du projet', 'rows' => 4]
            ])
            ->add('beginDate', DateType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('endDate', DateType::class, [
                'label' => 'Date de fin',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('estimateEnddate', DateType::class, [ 
                'label' => 'Estimation de fin',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('isFinished', CheckboxType::class, [
                'label' => 'Projet terminé ?',
                'required' => false,
                'attr' => ['class' => 'form-check-input']
            ])
            ->add('cost', NumberType::class, [
                'label' => 'Coût estimé',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Coût en euros']
            ])
            ->add('isSuccess', CheckboxType::class, [
                'label' => 'Projet réussi ?',
                'required' => false,
                'attr' => ['class' => 'form-check-input']
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'label' => 'Utilisateurs associés',
                'choice_label' => 'username',
                'multiple' => true,
                'attr' => ['class' => 'form-control']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}