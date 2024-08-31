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

class RegistrationController extends AbstractController
{

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        $emailExistError = false;
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

                // Set role
                $user->setRoles(['ROLE_ADMIN']);

                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute('back');
            }
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView(),
            'emailExistError' => $emailExistError,
        ]);
    }


}
