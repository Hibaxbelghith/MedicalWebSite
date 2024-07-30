<?php

namespace App\Form;

use App\Entity\Rendezvous;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RendezvousType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                ->add('nom', TextType::class)
                ->add('prenom', TextType::class)
                ->add('dateNaissance', DateType::class, [
                    'widget' => 'single_text',
                    'empty_data' => null,
                ])
                ->add('phone', TextType::class)
                ->add('message', TextareaType::class, [
                    'required' => false,
                ])
                ->add('typeRendezvous', ChoiceType::class, [
                    'choices' => [
                        '1er rendez-vous (60 min)' => '1er rendez-vous (60 min)',
                        'Déjà client : rendez-vous de réglage (20 min)' => 'Déjà client : rendez-vous de réglage (20 min)',
                    ],
                ]);

        }

        public
        function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => Rendezvous::class,
            ]);
        }
    }
