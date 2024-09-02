<?php

namespace App\Form;

use App\Repository\AdresseRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];
        $builder
            ->add('adresse',EntityType::class,[
                'label' => 'Choisissez adresse de livraison',
                'required' => true,
                'class' => 'App\Entity\Adresse',
                'multiple' => false,
                'expanded' => true, //non liste
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez choisir une adresse.',
                    ]),
                ],
                'query_builder' => function (EntityRepository $e) use ($user) {
                return $e->createQueryBuilder('a')
                    ->where('a.user = :user')
                    ->setParameter('user', $user->getId())
                    ->andWhere('a.isDeleted = false');
                }
                //'choices' => $user->getAdresses(),
            ])
            ->add('transporteur',EntityType::class,[
                'label' => 'Choisissez votre transporteur',
                'required' => true,
                'class' => 'App\Entity\Carrier',
                'multiple' => false,
                'expanded' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez choisir un transporteur.',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class,[
                'label' => 'Valider ma commande',
                'attr' => [
                    'class' => 'btn btn-success w-100'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'user' => array() //valeur par defaut de user dans form
        ]);
    }
}
