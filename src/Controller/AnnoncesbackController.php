<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Form\AnnoncesType;
use App\Service\PdfService;
use App\Repository\AnnoncesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/annoncesback')]
class AnnoncesbackController extends AbstractController
{
    #[Route('/', name: 'app_annoncesback_index', methods: ['GET'])]
    public function index(AnnoncesRepository $annoncesRepository): Response
    { 
        $annonces = $annoncesRepository->findAll();
    
        return $this->render('annoncesback/index.html.twig', [
            'annonces' => $annonces,
        ]);
    }

    #[Route('/new', name: 'app_annoncesback_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $annonce = new Annonces();
        $form = $this->createForm(AnnoncesType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($annonce);
            $entityManager->flush();

            return $this->redirectToRoute('app_annoncesback_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('annoncesback/new.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_annoncesback_show', methods: ['GET'])]
    public function show(Annonces $annonce): Response
    {
        return $this->render('annoncesback/show.html.twig', [
            'annonce' => $annonce,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_annoncesback_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Annonces $annonce, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AnnoncesType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_annoncesback_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('annoncesback/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }
    #[Route('/pdf/{id}', name: 'annonce.pdf')]
    public function generatePdfannonce(Annonces $annonces = null, PdfService $pdf) {
        // Assuming you want to render 'annoncesback/show.html.twig' for the PDF content
        $html = $this->renderView('annoncesback/index.html.twig', ['annonce' => $annonces]);
    
        // Assuming PdfService has a method to generate and display PDF from HTML content
        $pdf->showPdfFile($html);
    }
    #[Route('/tri/{order}', name: 'app_annoncesback_tri', methods: ['GET'])]
    public function tri(AnnoncesRepository $annoncesRepository, $order = 'asc'): Response
    {
        $annonces = $annoncesRepository->findBy([], ['createdAt' => $order]);

        return $this->render('annoncesback/index.html.twig', [
            'annonces' => $annonces,
        ]);
    }


    #[Route('/{id}', name: 'app_annoncesback_delete', methods: ['POST'])]
    public function delete(Request $request, Annonces $annonce, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$annonce->getId(), $request->request->get('_token'))) {
            $entityManager->remove($annonce);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_annoncesback_index', [], Response::HTTP_SEE_OTHER);
    }
}
