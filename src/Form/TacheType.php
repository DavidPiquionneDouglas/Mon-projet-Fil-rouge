<?php
namespace App\Form;

use App\Entity\Taches;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

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
            ->add('leaderProject');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Taches::class,
        ]);
    }
}
