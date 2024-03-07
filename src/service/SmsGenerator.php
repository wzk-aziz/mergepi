<?php
    // Update the path below to your autoload.php,
    // see https://getcomposer.org/doc/01-basic-usage.md
    require_once '/path/to/vendor/autoload.php';
    use Twilio\Rest\Client;

    $sid    = "ACa84fddb82424c4497c4c1dcfa0adee8f";
    $token  = "f4565827c3856312475bdd560d9f7af0";
    $twilio = new Client($sid, $token);

    $message = $twilio->messages
      ->create("+21626668173", // to
        array(
          "from" => "+19286156599",
          "body" => "vous avez reçu une nouvelle demande d'echange"
        )
      );

print($message->sid);