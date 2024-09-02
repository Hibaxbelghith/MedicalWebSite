<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Form\AdresseType;
use App\Provider\CartProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdresseController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entityManager)
    {

    }
    #[Route('/account/adresse', name: 'app_adresse')]
    public function index(): Response
    {
        return $this->render('FrontOffice/account/address.html.twig', [
            'controller_name' => 'AdresseController',
        ]);
    }

    #[Route('/account/adress/add', name: 'app_adresse_add')]
    public function addAdress(Request $request,CartProvider $cartProvider): Response
    {
        $adresse = new Adresse();
        $form = $this->createForm(AdresseType::class, $adresse);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $adresse->setUser($this->getUser());
            $this->entityManager->persist($adresse);
            $this->entityManager->flush();
            if($cartProvider->getCart()){
                return $this->redirectToRoute('app_commande');
            }
            return $this->redirectToRoute('app_adresse');

        }

        return $this->render('FrontOffice/account/add_address.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/account/adress/update/{id}', name: 'app_adresse_update')]
    public function updateAdress(Request $request,$id): Response
    {
        $adresse = $this->entityManager->getRepository(Adresse::class)->find($id);
        $form = $this->createForm(AdresseType::class, $adresse);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($adresse);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_adresse');

        }

        return $this->render('FrontOffice/account/update_address.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/account/adress/delete/{id}', name: 'app_adresse_delete')]
    public function deleteAdress(Request $request,$id): Response
    {
        $adresse = $this->entityManager->getRepository(Adresse::class)->find($id);
        $adresse->setDeleted(true);
            $this->entityManager->persist($adresse);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_adresse');

    }
}
