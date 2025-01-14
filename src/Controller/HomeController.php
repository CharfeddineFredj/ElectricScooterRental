<?php

namespace App\Controller;

use App\Entity\Announcement;
use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use App\Repository\AnnouncementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface; 
use App\Entity\Trotinette;
use App\Entity\Station;



class HomeController extends AbstractController
{
     private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/', name: 'app_homee' )]
    public function index(AnnouncementRepository $announcementRepository, ReservationRepository $reservationRepository): Response
    {
   
        $trotinettes = $this->entityManager->getRepository(Trotinette::class)->findAll();       
        $stations = $this->entityManager->getRepository(Station::class)->findAll(); // Récupération de toutes les stations
        $announcements = $announcementRepository->findAll();
 
        return $this->render('home/indexx.html.twig', [
             'controller_name' => 'HomeController',
             'trotinettes' => $trotinettes,
             'stations' => $stations,
             'announcements' => $announcements,
         ]);
        }
    

    #[Route('/dashboard', name: 'app_dashboard' )]
    public function dashboard(ReservationRepository $reservationRepository): Response
    {
        $reservations = $reservationRepository->findAll();

        return $this->render('dashboard/index.html.twig', [
            'reservations' => $reservations,
            'controller_name' => 'HomeController',
        ]);
    }
}
