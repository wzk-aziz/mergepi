<?php

namespace App\Controller;

use App\Entity\Echange;
use App\Form\Echange1Type;
use App\Repository\EchangeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/echangeback')]
class EchangeBackController extends AbstractController
{
    #[Route('/all', name: 'app_back_echange_index', methods: ['GET'])]
    public function index(EchangeRepository $echangeRepository): Response
    {
        return $this->render('echange_back/index.html.twig', [
            'echanges' => $echangeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_echange_back_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $echange = new Echange();
        $form = $this->createForm(Echange1Type::class, $echange);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($echange);
            $entityManager->flush();

            return $this->redirectToRoute('app_echangeB_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('echange_back/new.html.twig', [
            'echange' => $echange,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_echange_back_show', methods: ['GET'])]
    public function show(Echange $echange): Response
    {
        return $this->render('echange_back/show.html.twig', [
            'echange' => $echange,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_echange_back_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Echange $echange, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Echange1Type::class, $echange);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_echangeB_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('echange_back/edit.html.twig', [
            'echange' => $echange,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_echange_back_delete', methods: ['POST'])]
    public function delete(Request $request, Echange $echange, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$echange->getId(), $request->request->get('_token'))) {
            $entityManager->remove($echange);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_echangeB_index', [], Response::HTTP_SEE_OTHER);
    }
}
