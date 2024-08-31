<?php

namespace App\Form;

use App\Entity\Category;
use App\Filter\Search;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('SearchName',TextType::class,[
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'votre recherche'
                ]
            ])
            ->add('SearchCategory',EntityType::class,[
                'class' => Category::class,
                'label' => false,
                'required' => false,
                'multiple' => true,
                'expanded' => true, //cases a cocher
            ])

            ->add('submit', SubmitType::class,[
                'label' => 'Rechercher',
                'attr' => [
                    'class' => 'btn btn-lg btn-primary form-control',
                    'style' => 'background-color: #223a66;'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            'method' => 'GET',
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
