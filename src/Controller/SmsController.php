<?php
namespace App\Controller;
namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;
use Symfony\Component\Routing\Annotation\Route;


use Twilio\Rest\Client;

class TwilioService
{
    private $twilioClient;
    private $twilioPhoneNumber; // Ajoutez cette propriété

    public function __construct(string $sid, string $token, string $twilioPhoneNumber)
    {
        $this->twilioClient = new Client($sid, $token);
        $this->twilioPhoneNumber = $twilioPhoneNumber;
    }

    public function sendSms(string $to, string $message)
    {
        $this->twilioClient->messages->create(
            $to,
            [
                'from' => $this->twilioPhoneNumber, // Utilisez le numéro Twilio comme expéditeur
                'body' => $message
            ]
        );
    }
}
