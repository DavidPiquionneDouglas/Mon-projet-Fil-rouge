<?php
// src/Form/ChatMessageType.php
namespace App\Form;

use App\Entity\ChatMessage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChatMessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('content', TextareaType::class, [
            'label' => false,
            'attr' => [
                'rows' => 2,
                'placeholder' => 'Tapez votre message...',
                'class' => 'form-control'
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ChatMessage::class,
        ]);
    }
}
