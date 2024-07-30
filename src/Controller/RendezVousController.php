<?php

namespace App\Controller;

use App\Entity\Rendezvous;
use App\Form\RendezvousType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RendezVousController extends AbstractController
{

    //new appointment
    #[Route('/home/appointment', name: 'rendezVous')]
    public function appointment(Request $request, EntityManagerInterface $entityManager): Response
    {
        $rendezvous = new Rendezvous();

        $form = $this->createForm(RendezvousType::class, $rendezvous);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($rendezvous);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('FrontOffice/appointment.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
