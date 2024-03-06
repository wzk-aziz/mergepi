<?php

namespace App\Controller;

use App\Entity\Inventory;
use App\Form\InventoryType;
use App\Repository\InventoryRepository;
use App\Repository\ItemsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/inventory/back')]
class InventoryBackController extends AbstractController
{
    #[Route('/', name: 'app_inventory_back_index', methods: ['GET'])]
    public function index(InventoryRepository $inventoryRepository): Response
    {
        return $this->render('inventory_back/index.html.twig', [
            'inventories' => $inventoryRepository->findAll(),
        ]);
    }

    #[Route('/items/back', name: 'app_items_back_index', methods: ['GET'])]
    public function indexItems(ItemsRepository $itemsRepository): Response
    {
        return $this->render('inventory_back/indexitems.html.twig', [
            'items' => $itemsRepository->findAll(),
        ]);
    }
}
