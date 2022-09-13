<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


function send_email($username, $password, $phone, $name, $currency_code) {
    require 'SMTP/vendor/autoload.php';

    $conn = mysqli_connect("localhost", "u345800517_bets", "y+J10jgj]w", "u345800517_bets") or die($conn);

    //SEND EMAIL Code
    //mail($post_array['email'], "Welcome to Bets BR","Thanks for registering to https://www.bets.com.br/");
    $mail = new PHPMailer(true);
    include_once('./../gaccess.php');    

    try{
        //SMTP Server Confid
        $mail->SMTPDebug = false;
        $mail->isSMTP();
        $mail->Host       = 'smtp.titan.email';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = SMTP_USERNAME;                     //SMTP username
        $mail->Password   = SMTP_PASSWORD;                               //SMTP password
        $mail->SMTPSecure = 'STARTTLS';            //Enable implicit TLS encryption
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        
        $mail->CharSet = 'UTF-8';
        $mail->SetLanguage("de", 'language');

        //Recipients
        $mail->setFrom('suporte@bets.com.br', 'Bets BR');
        
        // if($_SERVER['REMOTE_ADDR'] == '103.93.136.54') {
        //     $mail->addAddress('ziyaindia78@gmail.com');     //Add a recipient
        // } else {
            $mail->addAddress('suporte@bets.com.br');     //Add a recipient
        //}
        //$mail->addAddress('ziya@reveredtech.comte@bets.com.br');     //Add a recipient
        //$mail->addAddress('ziya@reveredtech.com');     //Add a recipient
        //$mail->addReplyTo('suporte@bets.com.br');

        //Content
        $mail->isHTML(true);
        $mail->Subject = 'O usuário foi cadastrado no site www.Bets.com.br!';
        $mail->Body = 'Username: '.$username.'<br>Phone: '.$phone.'<br>Name: '.$name;
                
        $mail->send();
        //echo 'Mail has been sent successfully!';
        //$ip = getClientIP();	
        $ip = $_SERVER['REMOTE_ADDR'];	
        $Fire_Insert_Query = "INSERT INTO `users`(`Firstname`, `Lastname`, `Username`, `Password`, `Email`, `Phonenumber`, `ip`, `currency` ) VALUES ('".$name."','Last Name','".$username."','".$password."','NoEmail','".$phone."', '".$ip."', '".$currency_code."')";
        
        if($_SERVER['REMOTE_ADDR'] == '103.93.136.227'){echo $Fire_Insert_Query;}
        
        $Fired = mysqli_query($conn,$Fire_Insert_Query);
        
        //header("Location: https://bookielink.pro/");
        //header("Location: https://stats.bets.com.br/success/");
        return true;
        //exit();
    } catch (Excemption $e){
        //echo 'Mail could not be sent. Error: ', $mail->ErrorInfo;
        //exit();
        return true;
    }
    //SEND EMAIL Code ends

}   

function getClientIP() {

    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
?>
