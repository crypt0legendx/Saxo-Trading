<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


require 'vendor/autoload.php';

$mail = new PHPMailer(true);
try{
    //SMTP Server Confid
    $mail->SMTPDebug = 2;
    $mail->isSMTP();
    $mail->Host       = 'smtp.titan.email';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'suporte@bets.com.br';                     //SMTP username
    $mail->Password   = 'Mazlo2020!!!';                               //SMTP password
    $mail->SMTPSecure = 'STARTTLS';            //Enable implicit TLS encryption
    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('suporte@bets.com.br', 'Bets BR');
    $mail->addAddress('khan.mohammadazhar@gmail.com');     //Add a recipient
    $mail->addReplyTo('fayzhasan240@gmail.com');

    //Content
    $mail->isHTML(true);
    $mail->Subject = 'Obrigado por se inscrever no www.Bets.com.br!';
    $mail->Body = 'Temos orgulho de nossos serviços premium de apostas e negociações em apostas esportivas.
O serviço premium Bets PRO oferece a possibilidade de fazer apostas em vários sportsbooks através de apenas UMA conta e sempre obter as melhores odds disponíveis no mercado.
Usar o Bets através do serviço Bookielink Pro irá melhorar a maneira como você faz apostas, bem como os resultados das apostas.

Por favor, faça o login em nossa plataforma de apostas:
https://bookielink.pro/login

Você pode depositar por vários métodos:
PIX:
Skrill: depositos@bookielink.com
Neteller: Transferência para Merchant - Merchant Code: BLUE8387
CryptoCurrency: Bitcoin, Ethereum, USD Coin, Litecoin, Dai, Bitcoin Cash
https://commerce.coinbase.com/checkout/b19b1646-9e50-479f-ac67-2a0a170efb10

Se você tiver alguma dúvida ou precisar de ajuda, entre em contato conosco diretamente sobre o que for necessário: suporte@bets.com.br

';

    $mail->send();
    echo 'Mail has been sent successfully!';
} catch (Excemption $e){
    echo 'Mail could not be sent. Error: ', $mail->ErrorInfo;
}