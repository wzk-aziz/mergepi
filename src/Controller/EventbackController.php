<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\Event1Type;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;


#[Route('/eventback')]
class EventbackController extends AbstractController
{
    #[Route('/', name: 'app_eventback_index', methods: ['GET'])]
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('eventback/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_eventback_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $form = $this->createForm(Event1Type::class, $event);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer le fichier téléchargé depuis le formulaire
            $file = $form->get('image')->getData();
    
            // Vérifier si un fichier a été téléchargé
            if ($file) {
                // Générer un nom de fichier unique
                $filename = md5(uniqid()) . '.' . $file->guessExtension();
    
                // Déplacer le fichier téléchargé vers l'emplacement permanent sur le serveur
                $file->move($this->getParameter('uploads_directory'), $filename);
    
                // Définir le nom de fichier dans l'entité event
                $event->setImage($filename);
            }
    
            // Persistez et flushz l'entité event
            $entityManager->persist($event);
            $entityManager->flush();
    
            // Rediriger vers la page d'index des events
            return $this->redirectToRoute('app_eventback_index', [], Response::HTTP_SEE_OTHER);
        }
    
        // Afficher le formulaire
        return $this->renderForm('eventback/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }
    


    #[Route('/{id}', name: 'app_eventback_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('eventback/show.html.twig', [
            'event' => $event,
        ]);
    }



    #[Route('/{id}/edit', name: 'app_eventback_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        
        //************************** */
        $form = $this->createForm(Event1Type::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             // Récupérer le fichier téléchargé depuis le formulaire
             $file = $form->get('image')->getData();

             if ($file) {
                // Générer un nom de fichier unique
                $filename = md5(uniqid()) . '.' . $file->guessExtension();
    
                // Déplacer le fichier téléchargé vers l'emplacement permanent sur le serveur
                $file->move($this->getParameter('uploads_directory'), $filename);
    
                // Définir le nom de fichier dans l'entité event
                $event->setImage($filename);
            }

              // Persistez et flushz l'entité event
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_eventback_index', [], Response::HTTP_SEE_OTHER);
        }

        //afficher le formulaire
        return $this->renderForm('eventback/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_eventback_delete', methods: ['POST'])]
    public function delete(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_eventback_index', [], Response::HTTP_SEE_OTHER);
    }
}
