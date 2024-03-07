<?php

namespace App\Controller;

use App\Form\Echange1Type;
use App\Service\TwilioService;
use Twilio\Rest\Client;
use App\Entity\Echange;
use App\Form\EchangeType;
use Symfony\Component\Mime\Email;
use App\Repository\EchangeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Loader\Configurator\mailer;

#[Route('/echangeback')]
class EchangeBackController extends AbstractController
{
    #[Route('/', name: 'app_echange_index', methods: ['GET'])]
    public function index(EchangeRepository $echangeRepository): Response
    {
        return $this->render('echange/index.html.twig', [
            'echanges' => $echangeRepository->findAll(),
        ]);
    }
    #[Route('/{id}', name: 'app_echange_show', methods: ['GET'])]
    public function show(Echange $echange): Response
    {
        return $this->render('echange/show.html.twig', [
            'echange' => $echange,
        ]);
    }
    #[Route('/show/{id}', name: 'app_back_show', methods: ['GET'])]
    public function showB(Echange $echange): Response
    {
        return $this->render('echange_back/show.html.twig', [
            'echange' => $echange,
        ]);
    }
    #[Route('/{id}/edit', name: 'app_echange_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Echange $echange, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EchangeType::class, $echange);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //handle photos upload second fucntion 
            $file = $form->get('image')->getData(); // Get the uploaded file from the form
            if ($file) {
                //Handle photos upload
                $filename = md5(uniqid()) . '.' . $file->guessExtension();


                $file->move($this->getParameter('photos_dir'), $filename);
                $echange->setImage($filename);
            }
                $entityManager->persist($echange);
                $entityManager->flush();

            return $this->redirectToRoute('app_echange_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('echange/edit.html.twig', [
            'echange' => $echange,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_echange_delete', methods: ['POST'])]
    public function delete(Request $request, Echange $echange, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$echange->getId(), $request->request->get('_token'))) {
            $entityManager->remove($echange);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_echange_show', [], Response::HTTP_SEE_OTHER);
    }
    

}
