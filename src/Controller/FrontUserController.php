<?php

namespace App\Controller;

use App\Form\UpdateProfilePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class FrontUserController extends AbstractController
{
    public function __construct(public EntityManagerInterface $entityManager)
    {

    }

    #[Route('/home/account', name: 'app_account_user')]
    public function index(): Response
    {
        return $this->render('FrontOffice/account/index.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }

    #[Route('/home/update_password_user', name: 'app_account_update_password_user')]
    public function updatePassword(Request $request,UserPasswordHasherInterface $userHasher): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UpdateProfilePasswordType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $old_password = $form->get('oldPassword')->getData();
            if($userHasher->isPasswordValid($user,$old_password)){
                $new_password = $form->get('newPassword')->getData();
                $password = $userHasher->hashPassword(
                    $user,
                    $new_password
                );
                $user->setPassword($password);
                $this->entityManager->persist($user);
                $this->entityManager->flush();
            }

        }

        return $this->render('FrontOffice/updatePasswordUser.html.twig',[
            'form' => $form->createView(),
        ]);
    }
}
