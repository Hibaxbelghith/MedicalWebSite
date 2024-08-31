<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UpdateProfilePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class,[
                'label' => 'votre adresse mail',
                'disabled' => true,
            ])
            ->add('firstName',TextType::class,[
                'label' => 'votre nom',
                'disabled' => true,
            ])
            ->add('lastName',TextType::class,[
                'label' => 'votre prenom',
                'disabled' => true,
            ])
            ->add('oldPassword',PasswordType::class,[
                'label' => 'votre mot de passe actuel',
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre mot de passe actuel.',
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'mot de passe actuel',
                ]
            ])
            ->add('newPassword',RepeatedType::class,[
                'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'Les mots de passe ne correspondent pas',
                'label' => 'nouveau mot de passe',
                'required' => true,
                'first_options' => [
                    'label' => 'nouveau mot de passe',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez entrer un nouveau mot de passe.',
                        ]),           ],
                        'attr' => [
                        'placeholder' => 'nouveau mot de passe',
                    ]
                ],'second_options' => [
                    'label' => 'confirmez le mot de passe',
                        'constraints' => [
                            new NotBlank([
                                'message' => 'Veuillez confirmer le nouveau mot de passe.',
                            ]),],
                    'attr' => [
                        'placeholder' => 'confirmez le mot de passe',
                    ]
                ]
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
