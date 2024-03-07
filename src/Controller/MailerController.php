<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController
{
    #[Route('/mail', name: 'app_mailer')]
   public function index(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('wassim.frigui@esprit.tn')
            ->to('sample-recipient@binaryboxtuts.com')
            ->subject('Email de notification')
            ->text('votre reclamation à était envoyer avec succes.');
 
        $mailer->send($email);
        return $this->render('mailer/index.html.twig');
    }
}
