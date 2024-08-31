<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Filter\Search;
use App\Form\SearchType;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProduitFrontController extends AbstractController
{

    #[Route('/produitFront', name: 'app_produit_front', methods: ['GET'])]
    public function index(Request $request, ProduitRepository $produitRepository): Response
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);

        $page = max(1, $request->query->getInt('page', 1));
        $limit = 6;
        $offset = ($page - 1) * $limit;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produits = $produitRepository->findBySearchFilter($search, $offset, $limit);
            $totalProduits = $produitRepository->countBySearchFilter($search);
        } else {
            $produits = $produitRepository->findAllPaginated($offset, $limit);
            $totalProduits = $produitRepository->count([]);
        }

        return $this->render('FrontOffice/produits.html.twig', [
            'produits' => $produits,
            'form' => $form->createView(),
            'currentPage' => $page,
            'totalPages' => ceil($totalProduits / $limit),
        ]);
    }

    #[Route('/produitFront/{id}', name: 'app_produit_details', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('FrontOffice/details.html.twig', [
            'produit' => $produit,
        ]);
    }

}
