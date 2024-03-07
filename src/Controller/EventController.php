<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\QrCode;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Label\Alignment\LabelAlignmentLeft;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Component\HttpFoundation\JsonResponse;
#[Route('/event')]
class EventController extends AbstractController
{
    #[Route('/all', name: 'app_event_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository ,EventRepository $eventRepository, PaginatorInterface $paginator, Request $request): Response
    {
         $events = $eventRepository->findAll();
        $reservations = $reservationRepository->findAll();
        $reservationsByEvenement = array();
        foreach ($events as $event) {
            $reservationsByEvenement[strval($event->getId())] = 0;
        }
        foreach ($reservations as $reservation) {
            $oldSum = $reservationsByEvenement[strval($reservation->getEvent()->getId())];
            $reservationsByEvenement[strval($reservation->getEvent()->getId())] = $oldSum + 1;
        }
        $data = $eventRepository->findAll();
        
        $event = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            3 // Nombre d'éléments par page
        );
    
        return $this->render('event/index.html.twig', [
            'event' => $event,
            'reservationsByEvenement' => $reservationsByEvenement,
        ]);
    }
    #[Route('/searchByPlace', name: 'search_by_place', methods: ['GET'])]
    public function searchByPlace(Request $request, EventRepository $eventRepository): JsonResponse
    {
        $place = $request->query->get('place');
    
        // Recherche des événements par place
        $events = $eventRepository->findByPlace($place);
    
        // Convertir les entités Event en tableau pour la réponse JSON
        $results = [];
        foreach ($events as $event) {
            $results[] = [
                'id' => $event->getId(),
                'eventName' => $event->getEventName(),
                'capacity' => $event->getCapacity(),
                'startDate' => $event->getStartDate()->format('Y-m-d'),  // Format de date personnalisé
                'endDate' => $event->getEndDate() ? $event->getEndDate()->format('Y-m-d') : null,  // Vérifier si la date de fin est définie
                'place' => $event->getPlace(),
                'description' => $event->getDescription(),
                'image' => $event->getImage(),
                // Ajoutez d'autres champs si nécessaire
            ];
        }
    
        return $this->json($results);
    }
    
    #[Route('/loadAllEvents', name: 'load_all_events', methods: ['GET'])]
    public function loadAllEvents(EventRepository $eventRepository): JsonResponse
    {
        // Récupérer tous les événements depuis le repository
        $events = $eventRepository->findAll();
    
        // Convertir les entités Event en tableau pour la réponse JSON
        $results = [];
        foreach ($events as $event) {
            $results[] = [
                'id' => $event->getId(),
                'eventName' => $event->getEventName(),
                'capacity' => $event->getCapacity(),
                'startDate' => $event->getStartDate()->format('Y-m-d'),  // Format de date personnalisé
                'endDate' => $event->getEndDate() ? $event->getEndDate()->format('Y-m-d') : null,  // Vérifier si la date de fin est définie
                'place' => $event->getPlace(),
                'description' => $event->getDescription(),
                'image' => $event->getImage(),
                // Ajoutez d'autres champs si nécessaire
            ];
        }
    
        return $this->json($results);
    }
    
    #[Route('/generate_qr_code', name: 'generate_qr_code', methods: ['POST'])]
    public function generateQrCode(Request $request): Response
    {
        $text = $request->request->get('text');
        $qrCode = QrCode::create($text)
            ->setSize(250)
            ->setMargin(40)
            ->setForegroundColor(new Color(0, 0,0 )) // Dark blue foreground color
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::High); // Set error correction level to HIGH

        // Create label
        $label = Label::create("Khoudh&Het")
            ->setTextColor(new Color(255, 0, 0)) // Red text color
            ->setAlignment(LabelAlignment:: Left); // Align label to left

        // Create PNG writer
        $writer = new PngWriter();

        // Write QR code to PNG image with label
        $result = $writer->write($qrCode, label: $label);

        // Output QR code image to the browser
        return new Response($result->getString(), Response::HTTP_OK, ['Content-Type' => $result->getMimeType()]);
    }
    #[Route('/new', name: 'app_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($form ->getData());
            $entityManager->flush();

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_event_show', methods: ['GET'])]
    public function show(Event $event, EventRepository $eventRepository, ReservationRepository $reservationRepository): Response
    {
        /*$events = $eventRepository->findAll();
        $reservations = $reservationRepository->findAll();
        $reservationsByEvenement = array();
        foreach ($events as $event) {
            $reservationsByEvenement[strval($event->getId())] = 0;
        }
        foreach ($reservations as $reservation) {
            $oldSum = $reservationsByEvenement[strval($reservation->getEvent()->getId())];
            $reservationsByEvenement[strval($reservation->getEvent()->getId())] = $oldSum + 1;
        }*/
        return $this->render('event/show.html.twig', [
            'event' => $event,
            //'reservationsByEvenement' => $reservationsByEvenement,
        ]);
        
    }
  
    #[Route('/{id}/edit', name: 'app_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_event_delete', methods: ['POST'])]
    public function delete(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
    }


   /* #[Route('/search', name: 'search_event', methods: ['GET'])]
    public function searchEvent(Request $request, EventRepository $repo): Response
    {
        $query = $request->query->get('q');
        $results = $repo->searchByEventName($query);
        $results2 = $repo->searchByDateD($query);
        $results3 = $repo->searchByDateF($query);
        $results4 = $repo->searchByPlace($query);
       

    
        return $this->render('event/_search_results.html.twig', [
            'results' => $results,
            'results2' => $results2,
            'results3' => $results3,
            'results4' => $results4,
        ]);
    }*/

}
