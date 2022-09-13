<?php
require  './vendor/autoload.php';

use Twilio\Rest\Client;

$sid = "ACcbe505d1e5af15987755c57bd23b9faf";
$token = "68894eb18b1f2e0d05b0e5e71575aeb6";
$serviceId = "VA245c2b135e04f0d8e8ddfc11299f8331";

if (isset($_POST['submit'])) {
    $phone = $_POST['phone'];
    $twilio = new Client($sid, $token);
    $verification = $twilio->verify->v2->services($serviceId)
        ->verifications
        ->create($phone, "sms");
    if ($verification->status) {
        header("Location: verify.php?phone=$verification->to");
        exit();
    } else {
        echo 'Unable to send verification code';
    }
}
