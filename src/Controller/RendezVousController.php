<?php

namespace App\Controller;

use App\Entity\Rendezvous;
use App\Form\RendezvousType;
use App\Provider\SendMailProvider;
use App\Repository\RendezvousRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RendezVousController extends AbstractController
{

    //new appointment Front
    #[Route('/home/appointment', name: 'rendezVous')]
    public function add_appointment(Request $request, EntityManagerInterface $entityManager,SendMailProvider $sendMailProvider): Response
    {
        $rendezvous = new Rendezvous();
        $dateTime = new \DateTime();
        $dateTime->setTimezone(new \DateTimeZone('Africa/Tunis'));

        $form = $this->createForm(RendezvousType::class, $rendezvous);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($rendezvous);
            $entityManager->flush();
            //$sendMailProvider->sendMail($this->getUser());

            return $this->redirectToRoute('home');
        }

        return $this->render('FrontOffice/appointment.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    //Display rendezVous Dashboard
    #[Route('/back/appointment', name: 'dash_rendezVous')]
    public function Display(RendezvousRepository $rendezVousRepository): Response
    {
        $rendezVous = $rendezVousRepository->findAll();

        return $this->render('BackOffice/appointment.html.twig', [
                'rendezVous' => $rendezVous,
            ]
        );
    }}