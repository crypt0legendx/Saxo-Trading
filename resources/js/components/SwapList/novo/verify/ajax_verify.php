<?php
require  './vendor/autoload.php';

use Twilio\Rest\Client;

require_once 'twilio_config.php';
$twilio = new Client($sid, $token);

if (isset($_POST['submit'])) {
    try {
        $vCode = $_POST['code'];
        $phone = $_POST['phone'];

        $verification_check = $twilio->verify->v2->services($serviceId)
            ->verificationChecks
            ->create(
                $vCode,
                ["to" => "+" . $phone]
            );

        if ($verification_check->status == 'approved') {
            echo 'success';   exit(); 
        } else {
            echo 'error';exit();
        }
    }
    catch(Exception $e) {
        echo 'error';exit();
    }    
}
