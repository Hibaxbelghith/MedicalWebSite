<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Produit;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\SlugType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class, [
                'label' => 'Nom du produit',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Nom du produit'],
             ])
            ->add('imageProduit', FileType::class, [
                'label' =>  false,
                'multiple' => false,
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/gif',
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Les format valides sont {{ types }}.',
                    ])
                ],
                'attr' => ['class' => 'form-control',
                    'placeholder'=> 'importer image du produit',],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['class' => 'form-control',
                    'placeholder'=> 'description du produit',],
            ])
            ->add('prix', MoneyType::class, [
                'label' => 'Prix',
                'currency' => 'DT',
                'attr' => ['class' => 'form-control',
                    'placeholder'=> 'prix produit', ],
            ])
            ->add('categorie', EntityType::class, [
                'class' => Category::class,
                'choice_label'=> 'nom',
                'placeholder'=> 'Choisir categorie produit',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
