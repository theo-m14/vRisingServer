<?php

namespace App\Form;

use App\Entity\Server;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ServerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                TextType::class,
                ['constraints' => [
                    new NotBlank([
                        'message' => "Merci de renseigner un nom pour le serveur",
                    ]),
                    new Length([
                        'min' => 4,
                        'minMessage' => 'Le nom du serveur doit faire au moins {{ limit }} caractères',
                    ]),
                ],]
            )
            ->add('openDay', DateTimeType::class, array(
                'input' => 'datetime_immutable',
            ))
            ->add('wipe', CheckboxType::class, ['required' => false])
            ->add('wipe_date', DateTimeType::class, array(
                'input' => 'datetime_immutable',
            ))
            ->add('type', ChoiceType::class, [
                'choices'  => [
                    'PVP' => true,
                    'PVE' => false,
                ]
            ])
            ->add('description', TextareaType::class, ['constraints' => [
                new NotBlank([
                    'message' => "Merci de renseigner un nom pour le serveur",
                ]),
                new Length([
                    'min' => 50,
                    'minMessage' => 'La description du serveur doit faire au moins 50 caractères',
                ]),
            ],])
            ->add('clan_size', ChoiceType::class, [
                'choices'  => [
                    'Solo' => 1,
                    'Duo' => 2,
                    'Trio' => 3,
                    'Squad' => 4
                ]
            ])
            ->add('discord', TextType::class, ['required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Server::class,
        ]);
    }
}
