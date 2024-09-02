<?php
namespace App\Provider;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class CartProvider{

    private $session;

    public function __construct(private RequestStack $requestStack,private ProduitRepository $produitRepository){
        $this->session = $this->requestStack->getSession();
    }

    public function addCart($id){

        $cart = $this->session->get('cart', []);
        if(!empty($cart[$id])){
            $cart[$id]++;
        }else{
            $cart[$id] = 1;
        }

        $this->session->set('cart',$cart);
    }

    public function getCart(){
        return $this->session->get('cart');
    }

    public function removeCart() {
        $this->session->remove('cart');
    }

    public function deleteProduct($id){
        $cart = $this->session->get('cart', []);
        unset($cart[$id]);
        return $this->session->set('cart',$cart);
    }

    public function decreaseProduct($id){
        $cart = $this->session->get('cart', []);
        if($cart[$id] > 1){
            $cart[$id]--;
        }else{
            unset($cart[$id]);
        }
        $this->session->set('cart',$cart);
    }

    public function cartComplet()
    {
        $cartProduit = [];
        $cart = $this->getCart();
        if ($cart){
            foreach($cart as $id => $quantity) {
                $cartProduit [] = [
                    'produit' => $this->produitRepository->findOneById($id),
                    'quantite' => $quantity
                ];

            }}
        return $cartProduit;
    }

}