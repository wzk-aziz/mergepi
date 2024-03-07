<?php

namespace App\Controller;

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

#[Route('/echange')]
class EchangeController extends AbstractController

{
    private $twilioService;
    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    #[Route('/new', name: 'app_echange_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,MailerInterface $mailer): Response
    {   
        $email = (new Email())
            ->from('Khoudh&Het@esprit.tn')
            ->to('sample-recipient@binaryboxtuts.com')
            ->subject('Email de notification')
            ->text('vous avez une nouvelle demande d échange.');
 
        $mailer->send($email);

        $echange = new Echange();
        
        $form = $this->createForm(EchangeType::class, $echange);
        $formData =[
            'etat'=>'non validé'
        ];
        $form->setData($formData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             //handle photos upload second fucntion 
             $file = $form->get('image')->getData(); // Get the uploaded file from the form
             if ($file) {
                 //Handle photos upload
                 $filename = md5(uniqid()) . '.' . $file->guessExtension();
 
 
                 $file->move($this->getParameter('photos_dirr'), $filename);
                 $echange->setImage($filename);
             }
            $entityManager->persist($echange);
            $entityManager->flush();
            $message = "Vvous avez reçu une nouvelle demande d'echange.";
        $numeroUtilisateur = '+21626668173'; // Récupérer le numéro de téléphone depuis le formulaire
        $this->twilioService->sendSms($numeroUtilisateur, $message);

            

            return $this->redirectToRoute('app_echange_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('echange/new.html.twig', [
            'echange' => $echange,
            'form' => $form,
        ]);
    }


}
