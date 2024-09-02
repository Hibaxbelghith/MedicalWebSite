<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class UsersManagmentController extends AbstractController
{

    #[\Symfony\Component\Routing\Annotation\Route('/back/users', name: 'users')]
    public function users(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('Backoffice/users.html.twig',[
                'users' => $users,
            ]
        );
    }

    #[Route('/back/users/{id}/modifier', name: 'user_edit')]
    public function edit(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Créer le formulaire avec l'utilisateur existant
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('users');
        }

        return $this->render('Backoffice/userEdit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/back/users/{id}/bloquer', name: 'user_block')]
    public function bloquer(User $user, EntityManagerInterface $entityManager): RedirectResponse
    {
        // Si l'utilisateur est déjà bloqué, le débloquer
        if ($user->IsBlocked()) {
            $user->setBlocked(false);
        } else {
            // Sinon, le bloquer
            $user->setBlocked(true);
        }

        // Enregistrer les modifications dans la base de données
        $entityManager->persist($user);
        $entityManager->flush();

        // Redirection après avoir bloqué l'utilisateur
        return $this->redirectToRoute('users');
    }

    #[Route('/back/user/add', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(UserRepository $userRepository,Request $request, EntityManagerInterface $entityManager,SessionInterface $session, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        $emailExistError = false;
        if ($form->isSubmitted() && $form->isValid()) {

            $userExist = $userRepository->findOneByEmail($form->get('email')->getData());
            if ($userExist) {
                $form->get('email')->addError(new FormError('Cet email est déjà utilisé.'));
                $emailExistError = true;
            } else {
                // Retrieve the plain password from the form
                $plainPassword = $form->get('password')->getData();

                // Hash the password using the password hasher service
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);

                // Set the hashed password to the user entity
                $user->setPassword($hashedPassword);

                // Set role
                $user->setRoles(['ROLE_ADMIN']);

                $entityManager->persist($user);
                $entityManager->flush();

                $session->getFlashBag()->add('success', 'utilisateur ajouté succès !');
                return $this->redirectToRoute('users', [], Response::HTTP_SEE_OTHER);
            }}

            return $this->render('Backoffice/user_add.html.twig', [
                'user' => $user,
                'form' => $form,
            ]);
        }
    }
