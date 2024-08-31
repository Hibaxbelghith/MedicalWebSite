<?php

namespace App\Controller;

use App\Provider\CartProvider;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    public function __construct(private CartProvider $cartProvider,private ProduitRepository $produitRepository)
    {

    }
    #[Route('/mon-panier', name: 'app_cart')]
    public function index(): Response
    {
        $cartProduit = [];
        $cart = $this->cartProvider->getCart();
        if ($cart){
        foreach($cart as $id => $quantity) {
            $cartProduit [] = [
                'produit' => $this->produitRepository->findOneById($id),
                'quantite' => $quantity
            ];

        }}
        return $this->render('cart/index.html.twig', [
            'cartProduit' => $cartProduit,
        ]);
    }

    #[Route('/cart/add/{id}', name: 'add_cart')]
    public function add($id,SessionInterface $session): Response
    {
        $this->cartProvider->addCart($id);
        $session->getFlashBag()->add('success', 'Produit ajouté au panier avec succès !');
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove', name: 'remove_cart')]
    public function remove(): Response
    {
        $this->cartProvider->removeCart();
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/delete/product/{id}', name: 'delete_product_cart')]
    public function deleteProduct($id): Response
    {
        $this->cartProvider->deleteProduct($id);
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/decrease/product/{id}', name: 'decrease_product_cart')]
    public function decreaseProduct($id): Response
    {
        $this->cartProvider->decreaseProduct($id);
        return $this->redirectToRoute('app_cart');
    }


}
