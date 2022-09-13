<?php
require  './vendor/autoload.php';
//require   __DIR__ . '/connection.php';

use Twilio\Rest\Client;

$sid = "ACcbe505d1e5af15987755c57bd23b9faf";
$token = "68894eb18b1f2e0d05b0e5e71575aeb6";
$serviceId = "VA245c2b135e04f0d8e8ddfc11299f8331";
$twilio = new Client($sid, $token);

if (isset($_POST['submit'])) {
    //echo 'Reached';
    $vCode = $_POST['code'];
    $phone = $_POST['phone'];

    $verification_check = $twilio->verify->v2->services($serviceId)
        ->verificationChecks
        ->create(
            $vCode,
            ["to" => "+" . $phone]
        );

    //echo $verification_check->status;   

    if ($verification_check->status == 'approved') {
        // checks if user already exist in db
        // $query = "SELECT mobile FROM users WHERE mobile = $phone";
        // $result = $connection->query($query);

        // if ($result->num_rows > 0) {
        //     $alert = "Welcome back to your account!";
        // } else {
        //     // add new user to db
        //     $insertQuery = "INSERT INTO users (mobile) VALUES ('$phone')";
        //     $insertResult = $connection->query($insertQuery);
        //     if ($insertResult) {
        //         $alert = "Welcome, new user!";
        //     }
        // }

        echo 'Valid code entered!';    

    } else {
        echo 'Invalid code entered!';
    }
}
