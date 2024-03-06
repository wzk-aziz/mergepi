<?php

namespace App\Controller;

use App\Entity\Items;
use App\Form\ItemsType;
use App\Repository\ItemsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

#[Route('/items')]
class ItemsController extends AbstractController
{

    #[Route('/', name: 'app_items_index', methods: ['GET'])]
    public function index(ItemsRepository $itemsRepository, Request $request): Response
    {

        $page = $request->query->getInt('page', 1);
        $limit = 4; // Number of items per page

        $paginator = $itemsRepository->findAllPaginated($page, $limit);
        return $this->render('items/index.html.twig', [
            'items' => $paginator,
            'currentPage' => $page,
            'maxPages' => ceil(count($paginator) / $limit),
        ]);
    }


    #[Route('/new', name: 'app_items_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $item = new Items();
        $form = $this->createForm(ItemsType::class, $item, [
            'attr' => ['novalidate' => 'novalidate'],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //handle photos upload second fucntion 
            $file = $form->get('photos')->getData(); // Get the uploaded file from the form
            if ($file) {
                //Handle photos upload
                $filename = md5(uniqid()) . '.' . $file->guessExtension();


                $file->move($this->getParameter('photos_dir'), $filename);
                $item->setPhotos($filename);
            }


            $entityManager->persist($item);
            $entityManager->flush();

            return $this->redirectToRoute('app_items_index');
        }

        return $this->render('items/new.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_items_show', methods: ['GET'])]
    public function show(Items $item): Response
    {
        return $this->render('items/show.html.twig', [
            'item' => $item,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_items_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Items $item, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ItemsType::class, $item, [
            'attr' => ['novalidate' => 'novalidate'],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_items_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('items/edit.html.twig', [
            'item' => $item,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_items_delete', methods: ['POST'])]
    public function delete(Request $request, Items $item, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $item->getId(), $request->request->get('_token'))) {
            $entityManager->remove($item);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_items_index', [], Response::HTTP_SEE_OTHER);
    }
    /* #[Route('/items/search', name: 'search_items', methods: ['GET'])]
    public function searchItems(Request $request): Response
    {
        $searchTerm = $request->request->get('searchTerm');

        $item = $this->getDoctrine()->getRepository(Items::class)->findBySearchTerm($searchTerm);

        return $this->render('items/index.html.twig', [
            'item' => $item,
        ]);
    }*/
}
