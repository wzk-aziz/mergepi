<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Entity\Commnet;
use App\Form\CommnetType;
use App\Repository\CommnetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/c')]
class CommnetController extends AbstractController
{
    #[Route('/getall', name: 'app_commnet_getall', methods: ['GET'])]
    public function getAllcommnet(CommnetRepository $commnetRepository): Response
    {
        $commnet = $commnetRepository->findAll();
        $data = ["commnet"=>$commnet];
        return  $this->json($data);
    }
    #[Route('/', name: 'app_commnet_index')]
    public function index(CommnetRepository $commnetRepository,PaginatorInterface  $paginator, Request $request): Response
    {
        $commnet = $commnetRepository->findAll();
    $pagination = $paginator->paginate(
        $commnetRepository->paginationQuery(), /* query NOT result */
        $request->query->get('page', 1),
        3
    );
        return $this->render('commnet/index.html.twig', [
            'commnet' => $commnetRepository->findAll(),
            'pagination' => $pagination
        ]);
    }

    #[Route('/new/{id}', name: 'app_commnet_new', methods: ['GET', 'POST'])]
    public function new(Request $request,Annonces  $annonces , EntityManagerInterface $entityManager): Response
    {
        $commnet = new Commnet();
        $commnet->setDatecommnt(new \DateTime());
        $form = $this->createForm(CommnetType::class, $commnet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commnet->setAnnonces($annonces);
            $entityManager->persist($commnet);
            $entityManager->flush();
            $this->addFlash(
                'notice',
                'vous avez ajouter un commentaire !'
            );
    

            return $this->redirectToRoute('app_commnet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commnet/new.html.twig', [
            'commnet' => $commnet,
            'form' => $form,
        ]);
    }




    #[Route('/{id}/signaler', name: 'app_commnet_signaler', methods: ['GET'])]
    public function signaler(Request $request, Commnet $commnet, EntityManagerInterface $entityManager): Response
    {
        $commnet->setSignale(true);
        $entityManager->flush();

        $this->addFlash('success', 'Le commentaire a été signalé avec succès.');

        // Redirigez l'utilisateur vers la page où se trouvait le commentaire signalé
        return $this->redirectToRoute('app_commnet_show', ['id' => $commnet->getId()]);
    }

    #[Route('/getall', name: 'app_commnet_getall', methods: ['GET'])]
    public function getAllAnnounces(CommnetRepository $commnetRepository): Response
    {
        $commnet = $commnetRepository->findAll();
        $data = ["commnet"=>$commnet];
        return  $this->json($data);
    }

    #[Route('/{id}', name: 'app_commnet_show', methods: ['GET'])]
    public function show(Commnet $commnet): Response
    {
        return $this->render('commnet/show.html.twig', [
            'commnet' => $commnet,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commnet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commnet $commnet, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommnetType::class, $commnet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commnet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commnet/edit.html.twig', [
            'commnet' => $commnet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commnet_delete', methods: ['POST'])]
    public function delete(Request $request, Commnet $commnet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commnet->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commnet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commnet_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/commmnet/{id}/like', name: 'app_commnet_like_commnet', methods: ['GET'])]
    public function likeCommentaire(Request $request,CommnetRepository $commnetRepository, EntityManagerInterface $entityManager, $id): Response
    {
        $commnet = $commnetRepository->find($id);
        $commnet->setLiked(1);
        $entityManager->persist($commnet);
        $entityManager->flush();
        // After like, stay on the same page
        return $this->redirectToRoute('app_commnet_index');

    }
    //dislike commentaire
    #[Route('/commnet/{id}/dislike', name: 'app_commnet_dislike_commnet', methods: ['GET'])]
    public function dislikeCommentaire(Request $request, CommnetRepository $CommnetRepository,EntityManagerInterface $entityManager, $id): Response
    {
        $commnet = $CommnetRepository->find($id);
        $commnet->setLiked(-1);
        $entityManager->persist($commnet);
        $entityManager->flush();
        // After dislike, stay on the same page
        return $this->redirectToRoute('app_commnet_index');
    }
}



