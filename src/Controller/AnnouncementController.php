<?php

namespace App\Controller;
use Vich\UploaderBundle\Form\Type\VichImageType;
use App\Entity\Announcement;
use App\Form\AnnouncementType;
use App\Repository\AnnouncementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AnnouncementController extends AbstractController
{
    #[Route('/announcement', name: 'app_announcement_index', methods: ['GET'])]
    public function index(AnnouncementRepository $announcementRepository): Response
    {
        return $this->render('announcement/index.html.twig', [
            'announcements' => $announcementRepository->findAll(),
        ]);
    }

    #[Route('/newannounce', name: 'app_announcement_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $announcement = new Announcement();
    $form = $this->createForm(AnnouncementType::class, $announcement);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $currentDateTime = new \DateTime(); // Get the current date-time
        $announcement->setCreatedAt($currentDateTime); // Set the creation date
        $announcement->setUpdatedAt($currentDateTime); // Set the update date

        $entityManager->persist($announcement);
        $entityManager->flush();

        return $this->redirectToRoute('app_announcement_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('announcement/new.html.twig', [
        'announcement' => $announcement,
        'form' => $form->createView(),
    ]);
}


    #[Route('/{id}/announceshow', name: 'app_announcement_show', methods: ['GET'])]
    public function show(Announcement $announcement): Response
    {
        return $this->render('announcement/show.html.twig', [
            'announcement' => $announcement,
        ]);
    }

    #[Route('/{id}/edit/announce', name: 'app_announcement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Announcement $announcement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AnnouncementType::class, $announcement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_announcement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('announcement/edit.html.twig', [
            'announcement' => $announcement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/deleteannounce', name: 'app_announcement_delete', methods: ['POST'])]
    public function delete(Request $request, Announcement $announcement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$announcement->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($announcement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_announcement_index', [], Response::HTTP_SEE_OTHER);
    }
}
