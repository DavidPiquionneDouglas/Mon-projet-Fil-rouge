<?php
namespace App\Form;

use App\Entity\User;
use App\Entity\Taches;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class TacheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle')
            ->add('code')
            ->add('description')
            ->add('beginDate', DateTimeType::class)
            ->add('endDate', DateTimeType::class)
            ->add('estimateEndDate', DateTimeType::class, [
                'required' => false, 
            ])
            ->add('isFinished')
            ->add('isSuccess')
            ->add('user')
            ->add('leaderProject')
            ->add('assignedUser', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email', // ou 'nom' si tu as un champ nom
                'placeholder' => 'Sélectionner un utilisateur',
                'required' => false,
            ])
            ->add('save', SubmitType::class, ['label' => 'Créer Tâche']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Taches::class,
        ]);
    }
}
