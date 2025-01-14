<?php

namespace App\Service;

use App\Entity\Reservation;
use App\Entity\Offer;
use Doctrine\ORM\EntityManagerInterface;

class ReservationService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function finalizeReservation(Reservation $reservation): Reservation
    {
        // Logique pour sauvegarder la réservation
        $this->entityManager->persist($reservation);
        $this->entityManager->flush();

        // Vérifie si mtotal > 100 et crée une offre
        if ($reservation->getMtotal() > 100) {
            $offer = new Offer();
            $offer->setReservation($reservation);
            $offer->setDetails('Félicitations, vous avez une réduction spéciale sur votre prochaine réservation!');
            $this->entityManager->persist($offer);
            $this->entityManager->flush();
        }

        return $reservation;
    }
}
