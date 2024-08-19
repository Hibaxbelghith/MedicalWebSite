<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UsersManagmentController extends AbstractController
{

    #[\Symfony\Component\Routing\Annotation\Route('/home/users', name: 'users')]
    public function users(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('Backoffice/users.html.twig',[
                'users' => $users,
            ]
        );
    }

    #[Route('/home/users/{id}/modifier', name: 'user_edit')]
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

    #[Route('/home/users/{id}/supprimer', name: 'user_delete')]
    public function delete(User $user, EntityManagerInterface $entityManager): RedirectResponse
    {
        // Supprimer l'utilisateur
        $entityManager->remove($user);
        $entityManager->flush();

        // Redirection après suppression
        return $this->redirectToRoute('users');
    }
}
