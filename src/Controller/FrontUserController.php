<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class FrontUserController extends AbstractController
{

    #[Route('/registerClient', name: 'app_register_client')]
    public function register(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        $emailExistError=false;
        if ($form->isSubmitted() && $form->isValid()) {

            $userExist = $userRepository->findOneByEmail($form->get('email')->getData());
            if ($userExist) {
                $emailExistError = true;
            } else {
                // Retrieve the plain password from the form
                $plainPassword = $form->get('password')->getData();

                // Hash the password using the password hasher service
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);

                // Set the hashed password to the user entity
                $user->setPassword($hashedPassword);

                // Attribuer le rôle par défaut
                $user->setRoles(['ROLE_USER']);

                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute('home');
            }}

        return $this->render('FrontOffice/registrationClient.html.twig', [
            'form' => $form->createView(),
            'emailExistError' => $emailExistError,
        ]);
    }

    #[Route(path: '/loginClient', name: 'client_login')]
    public function login(AuthenticationUtils $authenticationUtils, UserRepository $userRepository): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        $foundUser = $userRepository->findOneByEmail($lastUsername);

        return $this->render('FrontOffice/loginClient.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'foundUser' => $foundUser,
        ]);
    }

}
