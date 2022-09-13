<?php
// ini_set('display_errors', 1);
// error_reporting(-1);
require  './vendor/autoload.php';

use Twilio\Rest\Client;

require_once 'twilio_config.php';

if (isset($_REQUEST['submit'])) {
    try {
        $phone = '+'.$_REQUEST['phone'];
        $twilio = new Client($sid, $token);
        $verification = $twilio->verify->v2->services($serviceId)
            ->verifications
            ->create($phone, "sms");
        if ($verification->status) {
            echo $verification->to; exit();
        } else {
            echo 'error'; exit();
        }
    }
    catch(Exception $e) {
        echo 'error'; exit();
    }
}
