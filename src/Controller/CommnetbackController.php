<?php

namespace App\Controller;
use App\Entity\Annonces;
use App\Entity\Commnet;
use App\Form\CommnetType;
use App\Repository\CommnetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commnetback')]
class CommnetbackController extends AbstractController
{
    #[Route('/', name: 'app_commnetback_index', methods: ['GET'])]
    public function index(CommnetRepository $commnetRepository): Response
    {
        return $this->render('commnetback/index.html.twig', [
            'commnets' => $commnetRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_commnetback_new', methods: ['GET', 'POST'])]
    public function new(Request $request,Annonces  $annonces, EntityManagerInterface $entityManager): Response
    {
        $commnet = new Commnet();
        $form = $this->createForm(CommnetType::class, $commnet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commnet->setAnnonces($annonces);
            $entityManager->persist($commnet);
            $entityManager->flush();
         

            return $this->redirectToRoute('app_commnetback_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commnetback/new.html.twig', [
            'commnet' => $commnet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commnetback_show', methods: ['GET'])]
    public function show(Commnet $commnet): Response
    {
        return $this->render('commnetback/show.html.twig', [
            'commnet' => $commnet,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commnetback_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commnet $commnet, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommnetType::class, $commnet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commnetback_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commnetback/edit.html.twig', [
            'commnet' => $commnet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commnetback_delete', methods: ['POST'])]
    public function delete(Request $request, Commnet $commnet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commnet->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commnet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commnetback_index', [], Response::HTTP_SEE_OTHER);
    }
}
