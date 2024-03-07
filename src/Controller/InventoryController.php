<?php

namespace App\Controller;

use App\Entity\Inventory;
use App\Entity\User;
use App\Form\InventoryType;
use App\Form\SearchType; // Add this line
use App\Repository\InventoryRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

#[Route('/inventory')]
class InventoryController extends AbstractController
{

    #[Route('/', name: 'app_inventory_index', methods: ['GET', 'POST'])]
    public function index(Request $request, InventoryRepository $inventoryRepository): Response
    {
        $form = $this->createForm(SearchType::class); // Create search form
        $form->handleRequest($request);

        $inventories = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $query = $form->get('query')->getData();
            $inventories = $inventoryRepository->search($query);
        } else {
            $inventories = $inventoryRepository->findAll();
        }

        return $this->render('inventory/index.html.twig', [
            'form' => $form->createView(), // Pass form to the view
            'inventories' => $inventories,
        ]);
    }

    #[Route('/new', name: 'app_inventory_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {   
     $inventory = new Inventory();

        $user = $this->getUser();
        $inventory->setUser($user);
    
        
        $inventory->setAddDate(new \DateTime());

        $form = $this->createForm(InventoryType::class, $inventory, [
            'attr' => ['novalidate' => 'novalidate'],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($inventory);
            $entityManager->flush();

            return $this->redirectToRoute('app_inventory_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('inventory/new.html.twig', [
            'inventory' => $inventory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_inventory_show', methods: ['GET'])]
    public function show(Inventory $inventory): Response
    {
        return $this->render('inventory/show.html.twig', [
            'inventory' => $inventory,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_inventory_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Inventory $inventory, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InventoryType::class, $inventory, [
            'attr' => ['novalidate' => 'novalidate'],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($inventory);
            $entityManager->flush();

            return $this->redirectToRoute('app_inventory_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('inventory/edit.html.twig', [
            'inventory' => $inventory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_inventory_delete', methods: ['POST'])]
    public function delete(Request $request, Inventory $inventory, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $inventory->getId(), $request->request->get('_token'))) {
            $entityManager->remove($inventory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_inventory_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/inventory/{userId}', name: 'view_inventory')]
    public function viewInventory(int $userId, EntityManagerInterface $entityManager): Response

    {
        // Fetch the current user
        $currentUser = $this->getUser();

        // Get the user whose inventory we want to view
        $userRepository = $entityManager->getRepository(User::class);
        $user = $userRepository->find($userId);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        // Get the inventories associated with the user
        $inventories = $user->getInventories(); // Use getInventories() to fetch all inventories

        return $this->render('inventory/view.html.twig', [
            'inventories' => $inventories, // Pass the collection of inventories
            'user' => $user,
            'currentUser' =>  $this->getUser(),
        ]);
    }
}
