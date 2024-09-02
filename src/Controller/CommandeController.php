<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\DetailsCommande;
use App\Form\CommandeType;
use App\Provider\CartProvider;
use App\Repository\AdresseRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Service\Attribute\Required;

class CommandeController extends AbstractController
{
    public function __construct(private AdresseRepository $ar,private CartProvider $cartProvider,private EntityManagerInterface $entityManager)
    {

    }
    #[Route('/ma_commande', name: 'app_commande')]
    public function index(): Response
    {
        $user = $this->getUser(); //user connected
        $address = $this->ar->getNonDeletedAddressByUserId($user->getId());
        if(empty($address)){
            return $this->redirectToRoute('app_adresse_add');
        }
        $form = $this->createForm(CommandeType::class,null,
        ['user'=>$user]);
        return $this->render('commande/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $this->cartProvider->cartComplet()
        ]);
    }

    #[Route('/ma_commande/add', name: 'app_commande_add')]
    public function add(Request $request): Response
    {
        $totaleCommande = 0;
        $user = $this->getUser(); //user connected
        $form = $this->createForm(CommandeType::class, null,
            ['user' => $user]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $carrier = $form->get('transporteur')->getData();
            $adresses = $form->get('adresse')->getData();
            $delivery = $adresses->getFirstName() . " " . $adresses->getLastName() . " <br> " . $adresses->getTelephone();
            ($adresses->getEntreprise()) ? $delivery .= " <br> " . $adresses->getEntreprise() : '';
            $delivery .= " <br> " . $adresses->getAdresse();
            $delivery .= " <br> " . $adresses->getCodePostal() . " <br> " . $adresses->getVille();
            $delivery .= " <br> " . $adresses->getPays();
            $commande = new Commande();
            $commande->setUser($user);
            $commande->setCreatedAt(new \DateTimeImmutable());
            $commande->setPaid(false);
            $commande->setNomCarrier($carrier->getNom());
            $commande->setPrixCarrier($carrier->getPrix());
            $commande->setDelivery($delivery);
            $totaleCommande = $carrier->getPrix();
            foreach ($this->cartProvider->cartComplet() as $value) {
                $DetailsCommande = new DetailsCommande();
                $DetailsCommande->setProduit($value['produit']->getNom());
                $DetailsCommande->setQuantite($value['quantite']);
                $DetailsCommande->setPrix($value['produit']->getPrix());
                $DetailsCommande->setTotale($value['produit']->getPrix() * $value['quantite']);
                $DetailsCommande->setCommande($commande);
                $totaleCommande += $value['produit']->getPrix() * $value['quantite'];
                $this->entityManager->persist($DetailsCommande);
            }
            $commande->setTotaleCommande($totaleCommande);
            $this->cartProvider->cartComplet();
            $this->entityManager->persist($commande);
            $this->entityManager->flush();

            return $this->render('commande/add.html.twig', [
                'cart' => $this->cartProvider->cartComplet(),
                'commande' => $commande
            ]);
        }

        return $this->redirectToRoute('app_cart');

    }
}
