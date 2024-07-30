<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(): Response
    {
        return $this->render('FrontOffice/base.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

    //OnlineTest
    #[Route('/home/test', name: 'test')]
    public function Audiotest(): Response
    {
        return $this->render('FrontOffice/OnlineTest.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

    //OnlineTest 1
    #[Route('/home/test1', name: 'test1')]
    public function Audiotest1(): Response
    {
        return $this->render('FrontOffice/OnlineTest1.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }




}