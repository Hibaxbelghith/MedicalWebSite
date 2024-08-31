<?php

namespace App\Controller;

use App\Repository\RendezvousRepository;
use App\Repository\UserRepository;
use Mailjet\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use \Mailjet\Resources;

class TestController extends AbstractController
{

    private $session;
    public function __construct(private RequestStack $requestStack)
    {
        $this->session = $this->requestStack->getSession();
    }
    #[Route('/home', name: 'home')]
    public function index(Security $security): Response
    {
        //$this->session->set('test', [
        //    'id' => 15
        //]);

        $session = $this->session->remove('test');
        $user = $security->getUser();

        return $this->render('FrontOffice/base.html.twig', [
            'session' => $session,
            'user' => $user,
        ]);
    }

    #[Route('/back', name: 'back')]
    public function Audiotest1(RendezvousRepository $rendezvousRepository): Response
    {
        $labels =  [2015, 2016, 2017, 2018, 2019, 2020, 2021, 2022, 2023, 2024, 2025];
        $data = [];
        $totalRendezVous = 0;
        foreach ($labels as $label){
            $qb = $rendezvousRepository->createQueryBuilder('r')
                ->select('COUNt(r.id)')
                ->where('r.dateAjout Like :label')
                ->setParameter('label', '%'.$label.'%')
                ->getQuery()->getSingleScalarResult();

            $data[] = $qb;
            $totalRendezVous += $qb;

        }

        return $this->render('BackOffice/base.html.twig',[
            'data' => json_encode($data),
            'totalRendezVous'=> $totalRendezVous,
            'labels'=> json_encode($labels),
        ]);
    }

    #[Route('/home/appareils', name: 'appareils')]
    public function Appareils(): Response
    {
        return $this->render('FrontOffice/appareils.html.twig'
        );
    }

    #[Route('/home/produits', name: 'produits')]
    public function Produits(): Response
    {
        return $this->render('FrontOffice/nosAppareils.html.twig'
        );
    }

    #[Route('/home/bouchons', name: 'bou')]
    public function Bouchons(): Response
    {
        return $this->render('FrontOffice/bouchons.html.twig'
        );
    }

    #[Route('/home/piles', name: 'piles')]
    public function piles(): Response
    {
        return $this->render('FrontOffice/piles.html.twig'
        );
    }










}