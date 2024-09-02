<?php

namespace App\Form;

use App\Entity\Adresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class AdresseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,[
                'label' => 'Quel est nom de votre adresse ?',
                'attr' =>
                    [
                        'placeholder' => 'nom de votre adresse',
                    ]
            ])
            ->add('firstname',TextType::class,[
                'label' => 'Entrez votre nom',
                'attr' =>
                    [
                        'placeholder' => 'votre nom',
                    ]
            ])
            ->add('lastname',TextType::class,[
                'label' => 'Entrez votre prénom',
                'attr' =>
                    [
                        'placeholder' => 'votre prénom',
                    ]
            ])
            ->add('entreprise',TextType::class,[
                'label' => 'Quel est nom de votre entreprise ? (facultatif)',
                'required' => false,
                'attr' =>
                    [
                        'placeholder' => 'entrz nom entreprise ',
                    ]
            ])
            ->add('adresse',TextType::class,[
                'label' => 'Votre adresse',
                'attr' =>
                    [
                        'placeholder' => 'Entrez votre adresse',
                    ]
            ])
            ->add('codePostal',TextType::class,[
                'label' => 'Votre code postal',
                'attr' =>
                    [
                        'placeholder' => 'votre code postal',
                    ]
            ])
            ->add('ville',TextType::class,[
                'label' => 'Votre ville',
                'attr' =>
                    [
                        'placeholder' => 'Entrez votre ville',
                    ]
            ])
            ->add('pays',countryType::class,[
                'label' => 'Votre pays',
                'attr' =>
                    [
                        'placeholder' => 'Choisir votre pays',
                    ]
            ])
            ->add('telephone',TextType::class,[
                'label' => 'Votre telephone',
                'attr' =>
                    [
                        'placeholder' => 'Entrez votre telephone',
                    ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer mon adresse',
                 'attr' => ['class' => 'btn btn-info','style' => 'background-color: #e12454;'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
