<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentUser = $this->security->getUser();
        $currentUserId = $currentUser?->getId();

        $builder
            ->add('email')
            ->add('password', PasswordType::class, [
                'label' => 'Password'])
            ->add('repeatPassword', PasswordType::class, [
                'constraints' => [new NotBlank(['message' => 'La confirmation du mot de passe est obligatoire.'])],
                'mapped' => false,
            ])
            ->add('firstName')
            ->add('lastName');

            // Add custom validation for password confirmation
        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($currentUserId) {
            $data = $event->getData();
            $form = $event->getForm();
            $roles = $data->getRoles();

            if ($data && $form->get('password')->getData() !== $form->get('repeatPassword')->getData()) {
                $form->get('repeatPassword')->addError(new FormError('Les mots de passe doivent correspondre.'));


            };

        // Check if the form is being used to edit an existing user
        if ($data instanceof User && $data->getId() !== null && $data->getId() === $currentUserId) {
            // If so, remove the email and password fields from the form
            $form->remove('email');
            $form->remove('password');
            $form->remove('role');

    }
        });}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
