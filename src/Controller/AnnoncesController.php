<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Entity\Commnet;
use App\Form\AnnoncesType;
use App\Repository\AnnoncesRepository;
use App\Repository\CommnetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


#[Route('/annonces')]
class AnnoncesController extends AbstractController
{
    #[Route('/getall', name: 'app_annonces_getall', methods: ['GET'])]
    public function getAllAnnounces(AnnoncesRepository $annoncesRepository): Response
    {
        $annonces = $annoncesRepository->findAll();
        $data = ["announces"=>$annonces];
        return  $this->json($data);
    }
    #[Route('/', name: 'app_annonces_index', methods: ['GET'])]
    public function index(AnnoncesRepository $annoncesRepository,PaginatorInterface  $paginator, Request $request): Response
    {
        $annonces = $annoncesRepository->findAll();
        $pagination = $paginator->paginate(
            $annoncesRepository->paginationQuery(), /* query NOT result */
            $request->query->get('page', 1),
            2
        );

        return $this->render('annonces/index.html.twig', [
            'annonces' => $annoncesRepository->findAll(),
            'pagination' => $pagination
        ]);
        
    }

    #[Route('/new', name: 'app_annonces_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        // Créez une nouvelle instance de votre entité annonce
        $annonce = new Annonces();
        $user = $this->getUser();
        $annonce->setUser($user);
    
        // Définissez la date de publication sur la date actuelle
        $annonce->setDatedepub(new \DateTime());
    
        // Créez le formulaire en utilisant l'instance d'annonce
        $form = $this->createForm(AnnoncesType::class, $annonce);
    
        // Gérez la soumission du formulaire
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrez l'annonce dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($annonce);
            $entityManager->flush();
            $this->addFlash(
                'notice',
                'vous avez ajouter une annonce !'
            );
    
            // Redirigez l'utilisateur vers une autre page après la création de l'annonce
            return $this->redirectToRoute('app_annonces_index');
        }
        
    
        // Rendre le formulaire Twig avec le formulaire créé
        return $this->render('annonces/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/{id}', name: 'app_annonces_show', methods: ['GET'])]
    public function show(Annonces $annonce): Response
    {
        return $this->render('annonces/show.html.twig', [
            'annonce' => $annonce,
        ]);
    }
    #[Route('/trie', name: 'app_club_trie', methods: ['GET'])]
    public function trie(AnnoncesRepository $AnnoncesRepository,CommnetRepository $commnetRepository,Request $request,PaginatorInterface $paginator): Response
    {  $donnnes=$commnetRepository->findAll();
        $donnees=$AnnoncesRepository->Trieparclub();
        $annonces = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3// Nombre de résultats par page
        );
        $commnetRepository = $paginator->paginate(
            $donnnes, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3// Nombre de résultats par page
        );
        
        return $this->render('annonces/index.html.twig', [
            'annonce' => $annonces,
            
        ]);
        
    }
  /*  #[Route('/search', name: 'search', methods: ['GET'])]
    public function searchBytitre(Request $request, AnnoncesRepository $repo): Response
    {
        // Get the search term from the query string
        $searchTerm = $request->query->get('q');
    
        // Perform the search using the repository method
        $results = $repo->searchByTitre($searchTerm); // Assuming 'searchByTitre' is the repository method for searching by title
    
        // Return the search results as JSON response
        return $this->json($results);
    }*/

    #[Route('/{id}/edit', name: 'app_annonces_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Annonces $annonce, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AnnoncesType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_annonces_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('annonces/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_annonces_delete', methods: ['POST'])]
    public function delete(Request $request, Annonces $annonce, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$annonce->getId(), $request->request->get('_token'))) {
            $entityManager->remove($annonce);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_annonces_index', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/annonces/{id}/like', name: 'app_annonces_like_annonces', methods: ['GET'])]
    public function likeCommentaire(Request $request,AnnoncesRepository $annoncesRepository, EntityManagerInterface $entityManager, $id): Response
    {
        $annonce = $annoncesRepository->find($id);
        $annonce->setLiked(1);
        $entityManager->persist($annonce);
        $entityManager->flush();
        // After like, stay on the same page
        return $this->redirectToRoute('app_annonces_index');

    }
    //dislike commentaire
    #[Route('/annonces/{id}/dislike', name: 'app_annonces_dislike_annonces', methods: ['GET'])]
    public function dislikeCommentaire(Request $request, AnnoncesRepository $annoncesRepository,EntityManagerInterface $entityManager, $id): Response
    {
        $annonce = $annoncesRepository->find($id);
        $annonce->setLiked(-1);
        $entityManager->persist($annonce);
        $entityManager->flush();
        // After dislike, stay on the same page
        return $this->redirectToRoute('app_annonces_index');
    }
}

