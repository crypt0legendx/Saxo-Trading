<?php
// ini_set('display_errors', 1);
// error_reporting(-1);
// Update the path below to your autoload.php,
// see https://getcomposer.org/doc/01-basic-usage.md
require_once './vendor/autoload.php';

use Twilio\Rest\Client;

// Find your Account SID and Auth Token at twilio.com/console
// and set the environment variables. See http://twil.io/secure
require_once './twilio_config.php';

$phone  = $_REQUEST['phone'];
$username = $_REQUEST['username'];
$password = $_REQUEST['password'];

//$body = "Obrigado por se inscrever com a Bets.com.br!\nAssista nosso vídeo sobre como começar a apostar aqui: \n https://stats.bets.com.br/success/ \n Também temos um chat para ajudar em qualquer dúvida.  \n Seu nome de usuário é: \n ".$username." \n Sua senha é: \n ".$password." \n (Favor alterar sua senha após o login na primeira vez) \n Oferecemos um Bônus de R$200 reals com qualquer depósito igual ou maior. \n Chat: https://direct.lc.chat/13882185/";

$body = "Obrigado por se inscrever com Bets!\nAssista nosso video sobre como comecar a apostar aqui:\nhttps://stats.bets.com.br/success/\nSeu nome de usuario e:\n".$username."\nSua senha e:\n".$password;


$twilio = new Client($sid, $token);
try {

    $message = $twilio->messages
                    ->create('+'.$phone, // to
                            [
                                "messagingServiceSid" => $Messaging_Service_SID, 
                                "body" => $body
                            ]
                    );


    // $message = $twilio->messages
    //                 ->create('+'.$phone, // to
    //                         [
    //                             "body" => $body,
    //                             "from" => $My_Twilio_phone_number
    //                         ]
    //                 );
    //print_r($message);
    //echo $message->errorCode;
    //echo $message->errorMessage;
    if(trim($message->errorCode) == '') {
        echo 'success';   exit();  
    } else {
        @mail("ziya@reveredtech.com", "Cadestro SMS Error", "Error Code - ".$message->errorCode.", Error Messgae - ".$message->errorMessage.", ".$phone.','.$username.','.$password);
        echo 'error';exit();
    }
}
catch(Exception $e) {
    @mail("ziya@reveredtech.com", "Cadestro SMS Error", json_encode($e).'- My_Twilio_phone_number, sid, tocken - '.$My_Twilio_phone_number.','.$sid.','.$token);
    echo 'error';exit();
}
