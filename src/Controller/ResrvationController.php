<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Offer; 
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use App\Service\ReservationService;
use Knp\Snappy\Pdf;
use App\Entity\Trotinette;





class ResrvationController extends AbstractController
{

    private $entityManager;
    

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    
    

    #[Route('/bookings', name: 'app_resrvation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('resrvation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }


    #[Route('/listscooter', name: 'app_scooter')]
    public function listscooter(): Response
    {       $trotinettes = $this->entityManager->getRepository(Trotinette::class)->findAll();       

        return $this->render('resrvation/listscooter.html.twig',  [
            'controller_name' => 'ReservationController',
            'trotinettes' => $trotinettes,

        ]);
        
    }

    
    #[Route('/manage_reservation', name: 'app_resrvation_manage', methods: ['GET'])]
    public function showreservation(ReservationRepository $reservationRepository): Response
    {
        // Get all reservations
        $reservations = $reservationRepository->findAll();
        
        // Count reservations by status
        $confirmedCount = $canceledCount = $pendingCount = 0;
        foreach ($reservations as $reservation) {
            switch ($reservation->getStatus()) {
                case 'Confirmed':
                    $confirmedCount++;
                    break;
                case 'Canceled':
                    $canceledCount++;
                    break;
                case 'Pending':
                    $pendingCount++;
                    break;
                default:
                    // Handle other statuses if needed
                    break;
            }
        }
    
        // Calculate percentages
        $totalReservations = count($reservations);
        $confirmedPercentage = $totalReservations > 0 ? round(($confirmedCount / $totalReservations) * 100) : 0;
        $canceledPercentage = $totalReservations > 0 ? round(($canceledCount / $totalReservations) * 100) : 0;
        $pendingPercentage = $totalReservations > 0 ? round(($pendingCount / $totalReservations) * 100) : 0;
    
        return $this->render('dashboard/index.html.twig', [
            'reservations' => $reservations,
            'confirmedPercentage' => $confirmedPercentage,
            'canceledPercentage' => $canceledPercentage,
            'pendingPercentage' => $pendingPercentage,
        ]);
    }





/*#[Route('/NewBooking', name: 'reservation_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response {
    $reservation = new Reservation();
    $form = $this->createForm(ReservationType::class, $reservation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($reservation);
        $entityManager->flush();
        return $this->redirectToRoute('app_resrvation_index');
    }

    return $this->render('resrvation/new.html.twig', ['form' => $form->createView()]);
}*/
/*#[Route('/NewBooking', name: 'reservation_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response {
    $reservation = new Reservation();
    $form = $this->createForm(ReservationType::class, $reservation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($reservation);
        $entityManager->flush();

        // Calculer le nombre d'heures gratuites en fonction de mtotal
        $freeHours = intdiv($reservation->getMtotal(), 100); // Utilisation de intdiv pour obtenir la division entière

        if ($freeHours > 0) {
            $offer = new Offer();
            $offer->setReservation($reservation);
            // Création du texte de l'offre en fonction des heures gratuites
            $offerDetails = sprintf('%d hour(s) free with a scooter!', $freeHours);
            $offer->setDetails($offerDetails);
            $entityManager->persist($offer);
            $entityManager->flush();

            // Message flash indiquant la création de la réservation avec l'offre
            $this->addFlash('success', 'Reservation created successfully with a special offer: ' . $offerDetails);
        } else {
            $this->addFlash('success', 'Reservation created successfully!');
        }

        return $this->redirectToRoute('app_resrvation_index');
    }

    return $this->render('resrvation/new.html.twig', ['form' => $form->createView()]);
}*/


#[Route('/NewBooking', name: 'reservation_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response {
    $idTrotinette = $request->query->get('idTrotinette');
    $reservation = new Reservation();
    $form = $this->createForm(ReservationType::class, $reservation);
    $form->handleRequest($request);
    $vitesse = $request->query->get('vitesse');
    $scooterPrice = $vitesse * 0.7;


    if ($form->isSubmitted() && $form->isValid()) {
        // Calcul des heures gratuites en fonction de mtotal
        $freeHours = intdiv($reservation->getMtotal(), 100);

        if ($freeHours > 0) {
            $offer = new Offer();
            $offer->setReservation($reservation);
            $offer->setStatusOffre('invalid'); // Définition du statutOffre par défaut
            $offerDetails = sprintf('%d hour(s) free with a scooter', $freeHours);
            $offer->setDetails($offerDetails);
            $entityManager->persist($offer);

            $offer->setCreatedAt(new \DateTime()); // Définition de la date de création
            $offer->setUpdatedAt(new \DateTime()); // Définition de la date de mise à jour
        }

      

        $entityManager->persist($reservation);
        $entityManager->flush();

        if ($freeHours > 0) {
            $this->addFlash('success', 'Reservation created successfully with a special offer: ' . $offerDetails);
        } else {
            $this->addFlash('success', 'Reservation created successfully!');
        }

        return $this->redirectToRoute('app_resrvation_index');
    }
    

    return $this->render('resrvation/new.html.twig', [ 'idTrotinette' => $idTrotinette,    'scooterPrice' => $scooterPrice,
    'form' => $form->createView()]);
}



    #[Route('/{id}', name: 'app_resrvation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('resrvation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

   /* #[Route('/{id}/edit', name: 'app_resrvation_edit', methods: ['GET', 'POST'])]
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
    }*/

    #[Route('/{id}/edit', name: 'app_resrvation_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(ReservationType::class, $reservation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Calculer le nombre d'heures gratuites en fonction de mtotal
        $freeHours = intdiv($reservation->getMtotal(), 100);

        if ($freeHours > 0) {
            $offer = $entityManager->getRepository(Offer::class)->findOneBy(['reservation' => $reservation]);
            if (!$offer) {
                $offer = new Offer();
                $offer->setReservation($reservation);
            }
            $offerDetails = sprintf('%d hour(s) free with a scooter!', $freeHours);
            $offer->setStatusOffre('invalid'); // Définition du statutOffre par défaut
            $offer->setDetails($offerDetails);
            $offer->setCreatedAt(new \DateTime()); // Définition de la date de création
            $offer->setUpdatedAt(new \DateTime()); // Définition de la date de mise à jour
            $entityManager->persist($offer);
        }

        $entityManager->flush();

        $this->addFlash('success', 'Reservation updated successfully' . ($freeHours > 0 ? " with special offer: " . $offerDetails : ""));

        return $this->redirectToRoute('app_resrvation_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('resrvation/edit.html.twig', [
        'reservation' => $reservation,
        'form' => $form,
    ]);
}

   
   #[Route('/{id}/editreservation', name: 'app_reservation_update_status', methods: ['POST'])]
    public function updateStatus(int $id, Request $request, CsrfTokenManagerInterface $csrfTokenManager, EntityManagerInterface $entityManager): Response
    {
        $token = new CsrfToken('update_status' . $id, $request->request->get('_token'));
        if (!$csrfTokenManager->isTokenValid($token)) {
            return $this->json(['status' => 'error', 'message' => 'Invalid CSRF token'], Response::HTTP_FORBIDDEN);
        }
    
        $reservation = $entityManager->getRepository(Reservation::class)->find($id);
    
        if (!$reservation) {
            return $this->json(['status' => 'error', 'message' => 'Reservation not found'], Response::HTTP_NOT_FOUND);
        }
    
        try {
            $newStatus = $request->request->get('status');
            $reservation->setStatus($newStatus);
            $entityManager->flush();
    
            // Redirect to a defined route after the update is successful
            return $this->redirectToRoute('app_resrvation_manage');
        } catch (\Exception $e) {
            return $this->json(['status' => 'error', 'message' => 'Error updating reservation: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
 

    
    #[Route('/generate_pdf/{id}', name: 'generatepdf')]
    public function generatePDF(Request $request, Pdf $pdfGenerator, int $id): Response
    {
        // Fetch reservation from the database based on ID
        $reservation = $this->entityManager->getRepository(Reservation::class)->find($id);
    
        // Check if reservation exists
        if (!$reservation) {
            throw $this->createNotFoundException('Reservation not found');
        }
    
        // Render the reservation into HTML (Twig template)
        $html = $this->renderView('resrvation/pdf.html.twig', ['reservation' => $reservation]);
    
        // Generate PDF using Snappy PDF
        $pdf = $pdfGenerator->getOutputFromHtml($html);
    
        // Return PDF as a downloadable file
        return new Response(
            $pdf,
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="reservation_' . $id . '.pdf"',
            ]
        );
    }


    #[Route('/{id}', name: 'app_resrvation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_resrvation_index', [], Response::HTTP_SEE_OTHER);
    }
}
