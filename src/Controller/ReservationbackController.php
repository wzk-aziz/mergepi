<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\Reservation1Type;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Options;
use Dompdf\Dompdf;

#[Route('/reservationback')]
class ReservationbackController extends AbstractController
{
    #[Route('/', name: 'app_reservationback_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservationback/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }
    public function generatePdf(): Response
    {
        // Récupérer toutes les réservations depuis la base de données
        $reservations = $this->getDoctrine()->getRepository(Reservation::class)->findAll();
    
        // Créer une instance de Dompdf avec les options nécessaires
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
    
        $dompdf = new Dompdf($pdfOptions);
    
        // Générer le HTML pour représenter chaque réservation
        $html = $this->renderView('reservationback/pdf_content.html.twig', [
            'reservation' => $reservations,
        ]);
    
        // Charger le HTML dans Dompdf et générer le PDF
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
    
        // Générer un nom de fichier pour le PDF
        $filename = 'liste_resevations.pdf';
    
        // Streamer le PDF vers le navigateur
        $response = new Response($dompdf->output());
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
    
        return $response;
    }
    
    #[Route('/new', name: 'app_reservationback_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(Reservation1Type::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reservationback_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservationback/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservationback_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservationback/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservationback_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Reservation1Type::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservationback_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservationback/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservationback_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservationback_index', [], Response::HTTP_SEE_OTHER);
    }
}
