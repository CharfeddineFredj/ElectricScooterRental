<?php

namespace App\Controller;

use Endroid\QrCode\QrCode;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Form\ImportTrotinettesType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Entity\Trotinette;
use App\Entity\Station;
use App\Entity\Reservation;
use App\Repository\TrotinetteRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Repository\ReservationRepository;
use App\Repository\StationRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Knp\Component\Pager\PaginatorInterface;

use Doctrine\Persistence\ManagerRegistry;
use App\Form\TrotinetteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Doctrine\ORM\EntityManagerInterface;

#[Route('/trot')]
class TrotinetteController extends AbstractController{
    private $stationRepository;

    public function __construct(StationRepository $stationRepository)
    {
        $this->stationRepository = $stationRepository;
    }
#[Route('/my_commandes', name: 'app_my_commandes')]
public function my_commandes(Request $request, TrotinetteRepository $trotinetteRepository, StationRepository $stationRepository): Response
{
    // Récupérer le nom de la station depuis les paramètres GET
    $stationName = $request->query->get('stationName');

    // Récupérer les données de la station
    $station = $stationRepository->findOneBy(['name' => $stationName]);

    // Vérifier si la station existe
    if (!$station) {
        throw $this->createNotFoundException('Station not found');
    }

    // Convertir les données en format JSON pour le code QR
    $qrData = [
        'station' => $station->getName(), // Nom de la station
    ];
    $jsonData = json_encode($qrData);

    // Générer l'URL pour l'API du code QR
    $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($jsonData);

    // Passer les données à la vue Twig
    return $this->render('front/qrcode.html.twig', [
        'qrCodeUrl' => $qrCodeUrl,
    ]);
}
#[Route('/art/generate-excel', name: 'app_generate_excel')]
public function generateExcel(ExcelController $excelController): Response
{
    $filename = $excelController->exportTrotinettesExcel();

    // Generate the full path to the Excel file
    $filePath = $this->getParameter('kernel.project_dir') . '/public/excel/' . $filename;

    // Check if the file exists
    if (!file_exists($filePath)) {
        throw $this->createNotFoundException('The file does not exist.');
    }

    // Return the file as a response
    return $this->file($filePath);
}

   
#[Route('/statistiques', name: 'statistiques')]
public function statistiques(EntityManagerInterface $entityManager): Response
    {
        $stationsWithTrottinettesCount = $entityManager->createQueryBuilder()
            ->select('s.name AS station_name', 'COUNT(t.idTrotinette) AS trotinettes_count')
            ->from(Trotinette::class, 't')
            ->leftJoin('t.idStation', 's')
            ->groupBy('s.name')
            ->getQuery()
            ->getResult();

        return $this->render('statistiques/index.html.twig', [
            'stationsWithTrottinettesCount' => $stationsWithTrottinettesCount,
        ]);
    }


#[Route('/trotinette', name: 'trotinette_add')]
public function add(Request $request, EntityManagerInterface $entityManager, TrotinetteRepository $trotinetteRepository,  StationRepository $stationRepository): Response
{
    // Récupérer l'ID de la station depuis la requête
    $trotinette = new Trotinette();
    $form = $this->createForm(TrotinetteType::class, $trotinette);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Récupérer le cinéma associé à l'événement
        $station = $trotinette->getIdstation();

        if (!$station) {
            throw $this->createNotFoundException('Cinema not found for this event.');
        }

        // Vérifier le nombre maximal d'événements associés à ce cinéma
        $maxEventsPerstation = 3; // Définir le nombre maximal d'événements par cinéma

        // Compter le nombre actuel d'événements associés à ce cinéma
        $currentEventCount = $entityManager->getRepository(Trotinette::class)
            ->createQueryBuilder('e')
            ->select('COUNT(e.idTrotinette)')
            ->where('e.idStation = :station')
            ->setParameter('station', $station)
            ->getQuery()
            ->getSingleScalarResult();

        if ($currentEventCount >= $maxEventsPerstation) {
            // Afficher un message d'erreur ou rediriger avec un message
            $this->addFlash('error', 'Le nombre maximal de trotinettes pour cette station a été atteint.');
            // Vous pouvez rester sur la même page en retournant simplement la vue avec le formulaire
            return $this->render('trotinette/ajouter.html.twig', [
                'trotinette' => $trotinette,
                'form' => $form->createView(),
            ]);
        }

        // Si la limite n'est pas atteinte, persistez le nouvel événement
        $entityManager->persist($trotinette);
        $entityManager->flush();

        $this->addFlash('success', 'Trotinette ajoutée avec succès.');

        return $this->redirectToRoute('app_getAll');
    }

    return $this->render('trotinette/ajouter.html.twig', [
        'trotinette' => $trotinette,
        'form' => $form->createView(),
    ]);
}

#[Route('/getAll', name: 'app_getAll')]
public function getAll(Request $request , TrotinetteRepository $repo , PaginatorInterface $paginator): Response
{   
    $page = $request->query->getInt('page', 1);
    $speed = $request->query->get('speed');

    if($speed) {
        $trotinettes = $repo->findBySpeed($speed);
    } else {
        $trotinettes = $repo->findAll();
    }

    $trotinettes = $paginator->paginate(
        $trotinettes,
        $page,
        5
    );

    if ($request->isXmlHttpRequest()) {
        $html = $this->renderView('trotinette/index.html.twig', [
            'trotinettes' => $trotinettes,
        ]);

        return new JsonResponse([
            'html' => $html,
        ]);
    }

    return $this->render('trotinette/index.html.twig', [
        
        'c' => $trotinettes,
    ]);
}
    #[Route('/update/{id}', name: 'trotinette_update')]
    public function update(Request $request , TrotinetteRepository $repo,EntityManagerInterface $entityManager, $id): Response
    {
        $trotinette = $repo->find($id);
        $form = $this->createForm(TrotinetteType::class , $trotinette);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->flush();

            return $this->redirectToRoute('app_getAll');
        }    

        return $this->render('trotinette/ajouter.html.twig' , 
        [
            'form'=>$form->createView()
        ]);
    }

    #[Route('/delete/{id}' , name:'deleteT')]
    public function delete(ManagerRegistry $manager , TrotinetteRepository $repo, $id)
    {
       $trotinette = $repo->find($id);
       if($trotinette)
       {
        $manager->getManager()->remove($trotinette);
        $manager->getManager()->flush();
        return $this->redirectToRoute('app_getAll');
       }
       else return new Response("there is no Trotinette with this ID");
    }
}
