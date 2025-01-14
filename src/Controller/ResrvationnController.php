<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use App\Entity\Offer; 

#[Route('/resrvation')]
class ResrvationnController extends AbstractController
{
    #[Route('/', name: 'app_resrvation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('resrvation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_resrvation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('app_resrvation_index', [], Response::HTTP_SEE_OTHER);
        }
         

        return $this->render('resrvation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
            
        ]);


    }

    #[Route('/{id}', name: 'app_resrvation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('resrvation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_resrvation_edit1', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_resrvation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('resrvation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_resrvation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $offerRepository = $entityManager->getRepository(Offer::class);
        $offers = $offerRepository->findBy(['reservation' => $reservation]);
    
        foreach ($offers as $offer) {
            // Remove associated offer(s)
            $entityManager->remove($offer);
        }
    
        // Flush the changes to delete the associated offers
        $entityManager->flush();
    
        // Now delete the reservation
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('app_resrvation_index', [], Response::HTTP_SEE_OTHER);
    }
}
