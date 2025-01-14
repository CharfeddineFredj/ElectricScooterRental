<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Trotinette;
use App\Entity\Station;
use Doctrine\ORM\EntityManagerInterface; // Importez le gestionnaire d'entités

class FrontController extends AbstractController
{
    private $entityManager; // Déclarez une propriété pour stocker le gestionnaire d'entités

    // Injectez le gestionnaire d'entités dans le constructeur du contrôleur
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/front', name: 'app_homee')]
    public function index(): Response
    {
       // Utilisez $this->entityManager pour accéder au gestionnaire d'entités
       $trotinettes = $this->entityManager->getRepository(Trotinette::class)->findAll();       
       $stations = $this->entityManager->getRepository(Station::class)->findAll(); // Récupération de toutes les stations

       return $this->render('home/indexx.html.twig', [
            'controller_name' => 'FrontController',
            'trotinettes' => $trotinettes,
            'stations' => $stations,
        ]);
    }
}
