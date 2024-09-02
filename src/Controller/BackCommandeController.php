<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\User;
use App\Form\BackCommandeType;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/back/commande')]
class BackCommandeController extends AbstractController
{
    #[Route('/', name: 'app_back_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository): Response
    {

        return $this->render('back_commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),

        ]);
    }

    #[Route('/new', name: 'app_back_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commande = new Commande();
        $form = $this->createForm(BackCommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('app_back_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back_commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_back_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('back_commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_back_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BackCommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_back_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back_commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_back_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_back_commande_index', [], Response::HTTP_SEE_OTHER);
    }

}
