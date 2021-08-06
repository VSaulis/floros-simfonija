<?php

namespace App\Form\Type;

use App\Model\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class MessageType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => $this->translator->trans('message.name'),
                'attr' => [
                    'placeholder' => $this->translator->trans('message.name')
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => $this->translator->trans('message.email'),
                'attr' => [
                    'placeholder' => $this->translator->trans('message.email')
                ]
            ])
            ->add('phone', TextType::class, [
                'label' => $this->translator->trans('message.phone'),
                'attr' => [
                    'placeholder' => $this->translator->trans('message.phone')
                ]
            ])
            ->add('subject', TextType::class, [
                'label' => $this->translator->trans('message.subject'),
                'attr' => [
                    'placeholder' => $this->translator->trans('message.subject')
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => $this->translator->trans('message.message'),
                'attr' => [
                    'placeholder' => $this->translator->trans('message.message')
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}