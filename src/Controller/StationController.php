<?php

namespace App\Controller;

use App\Entity\Trotinette;
use App\Entity\Station;
use App\Repository\StationRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\StationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


class StationController extends AbstractController
{
    #[Route('/base', name: 'app_station')]
    public function index(): Response
    {
        return $this->render('baseAdmin.html.twig');
    }

    #[Route('/gets', name: 'app_gets')]
    public function getAll(Request $request , StationRepository $repo): Response
    {
        $stations = $repo->findAll();
        return $this->render('station/indexs.html.twig' , 
        [
            'c' =>$stations
        ]);
    }

    #[Route('/station', name: 'station_add')]
    public function add(Request $request , EntityManagerInterface $entityManager): Response
    {
        $station = new Station();
        $form = $this->createForm(StationType::class , $station);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($station);
            $entityManager->flush();

            return $this->redirectToRoute('app_gets');
        }    

        return $this->render('station/ajouters.html.twig' , 
        [
            'form'=>$form->createView()
        ]);
    }

    #[Route('/updatee/{id}', name: 'station_update')]
    public function update(Request $request , StationRepository $repo,EntityManagerInterface $entityManager, $id): Response
    {
        $station = $repo->find($id);
        $form = $this->createForm(StationType::class , $station);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->flush();

            return $this->redirectToRoute('app_gets');
        }    

        return $this->render('station/ajouters.html.twig' , 
        [
            'form'=>$form->createView()
        ]);
    }

    #[Route('/deletee/{id}' , name:'deleteS')]
    public function delete(ManagerRegistry $manager , StationRepository $repo, $id)
    {
       $station = $repo->find($id);
       if($station)
       {
        $manager->getManager()->remove($station);
        $manager->getManager()->flush();
        return $this->redirectToRoute('app_gets');
       }
       else return new Response("there is no Trotinette with this ID");
    }
}
