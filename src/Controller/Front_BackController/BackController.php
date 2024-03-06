<?php

namespace App\Controller\Front_BackController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AnnoncesRepository;

class BackController extends AbstractController
{
    #[Route('/back', name: 'app_back')]
    public function index(AnnoncesRepository $annoncesRepository): Response
    {
        return $this->render('back/index.html.twig', [
            'controller_name' => 'BackController',
            'annonces' => $annoncesRepository->findAll(),
        ]);
    }
}
