<?php
require_once 'lib/Request.php';
$requestModel = new Request();
$ip = $requestModel->getIpAddress();
//$isValidIpAddress = $requestModel->isValidIpAddress($ip);
$geoLocationData = $requestModel->getLocation($ip);
$cc = $geoLocationData['country_code'];
$currency_code = $geoLocationData['currency_code'];
//echo '<pre>';print_r($geoLocationData);echo '</pre>';
// Array
// (
//     [ip] => 103.93.136.193
//     [success] => 1
//     [type] => IPv4
//     [continent] => Asia
//     [continent_code] => AS
//     [country] => India
//     [country_code] => IN
//     [country_flag] => https://cdn.ipwhois.io/flags/in.svg
//     [country_capital] => New Delhi
//     [country_phone] => +91
//     [country_neighbours] => CN,NP,MM,BT,PK,BD
//     [region] => Madhya Pradesh
//     [city] => Indore
//     [latitude] => 22.7195687
//     [longitude] => 75.8577258
//     [asn] => AS135826
//     [org] => Syntego Technologies India Private Limited
//     [isp] => Syntego Technologies India Private Limited
//     [timezone] => Asia/Calcutta
//     [timezone_name] => India Standard Time
//     [timezone_dstOffset] => 0
//     [timezone_gmtOffset] => 19800
//     [timezone_gmt] => GMT +5:30
//     [currency] => Indian Rupee
//     [currency_code] => INR
//     [currency_symbol] => ₹
//     [currency_rates] => 75.938
//     [currency_plural] => Indian rupees
//     [completed_requests] => 361
// )


// Code 	Continent name
// AF 	Africa
// AN 	Antarctica
// AS 	Asia
// EU 	Europe
// NA 	North america
// OC 	Oceania
// SA 	South america


$currency_code = 'BRL';
$phone_format = '(00) 00000-0000';

if($geoLocationData['continent_code'] == 'EU')  {
    $currency_code = 'EURO';
    $phone_format = '(000) 0000-0000';
} else if($geoLocationData['continent_code'] == 'NA')  {
    $currency_code = 'USD';
    $phone_format = '(000) 000-0000';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bets.com.br Cadastro</title>
<link rel="stylesheet" href="./style.css">
<meta name = "apostas esportiva, notícias , Brasil" content = "O maior site de conteúdo esportivo, notícias e apostas do Brasil. R$200 de bônus com a inscrição!" />
<style>
#btng{
    width : 350px;
    padding:8px;
    text-align: center;
}

div#registration .modal-inner {
    margin-top: 70px;
}
#imgl{
    transform: scaleY(1.5);
    transform: rotate(4deg);
}
.d-none{
    display:none;
}
.name_fil {
    display: flex;    
    margin: 0 auto 15px;
}
.inner_input_f {
    max-width: 85%;
    margin: 0 auto;
    min-width: 85%;
}
.name_fil span {
    background: #f5f5f5;
    display: inline-block;
    height: 2.5rem;
    border-width: 1px;
    border-top-left-radius: .25rem;
    background-color: rgb(245 245 245/var(--tw-bg-opacity));
    border-bottom-left-radius: .25rem;
    line-height: 35px;
    min-width: 80px;
}
.iti {
    width: 100%;
}
@media only screen and (min-device-width : 320px) and (max-device-width : 480px){
div#registration {
    overflow: scroll;
}
#btng {
    width: 100%;
    padding: 8px;
    text-align: center;
    font-size: 16px;
}
}
@media only screen and (min-device-width : 280px) and (max-device-width : 1024px) {
    .mobile_reverse {
    flex-direction: column;
    align-items: center;
}
.main__person_first {
    left: -70px;
    position: relative;
}
.mobile_reverse .main__title {
    text-align: center;
}
.mobile_reverse .main__title h6 {
    margin: 6px auto 6px;
}
.mobile_reverse .main__subtitle h6 {
    margin: 0 auto;
}
.ganhe_box {
    justify-content: center;
}
.ganhe_box a.underline {
    margin-top: 15px;
}
.icon_txt_sec {
    justify-content: space-between;
}
.icon_txt_sec p.text-white {
    color: #000;
}
.mobile_reverse .main__title a.logo {
    position: relative;
    margin-bottom: 20px;
}
.main__titles {
    width: 100%;
    margin: 0 auto 200px;
       top: -40px;
}
.icon_txt_sec .flex.items-center {
    height: 22px;
}
#registration .modal-body {
    padding: 2rem 1rem;
}
#registration img {
    display: inline-block;
    margin-bottom: 45px !important;
    max-height: 100px;
}
#registration .main__subtitle {
    margin: 6px 0;
}
#registration .main__titles {
    margin-bottom: 60px;
    margin-top: 30px;
}
.border-t label input {
    max-width: 10px;
}
.border-t label {
    font-size: 11px;
}

.name_fil span {
    min-width: 80px;
}

}
</style>
<link rel=”stylesheet” href=”css/bootstrap.css”>
<link rel=”stylesheet” href=”css/bootstrap-responsive.css”>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-NXJHB2M');</script>
<!-- End Google Tag Manager -->

<script src="//bets.refersion.com/tracker/v3/pub_2a270c807d4b28ab2b61.js"></script>
<script>_refersion();</script>

</head>
<body>

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NXJHB2M"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    
    <div class="relative overflow-hidden">
        <img src="assets/img/custom-background.jpg" alt="image" class="absolute h-full w-full object-cover left-0 top-0">
        <img src="assets/img/glare.png" alt="image" class="main__background_first absolute h-full w-full object-cover left-0 top-0 opacity-0">
        <img src="assets/img/soffit.png" alt="image" class="main__background_second absolute h-full w-full object-cover left-0 top-0 opacity-0">
        <div class="main__persons absolute top-1/2 -translate-y-1/2 right-[-37vw] w-[90vw] h-[53vw]">
            <div class="main__person main__person_first">
                <img src="assets/img/custom-men.png" alt="man">
            </div>
        </div>
        <div class="container max-w-5xl relative min-h-screen flex flex-col">
            <div class="py-5 text-center sm:text-left">
                
            </div>

            <div class="relative mobile_reverse gap-y-10 flex flex-col-reverse sm:flex-col items-start justify-center flex-grow py-5">
                <div>
                    <div class="main__titles mb-20">
                        <div class="main__title">
                            <a href="" class="logo inline-block">
                                <img src="assets/img/bets.png" alt="" width="140" id="imgl">
                            </a>
                            <h6 class="bg-white text-[#ff4020] py-3 px-5 m-0 md:text-4xl lg:text-5xl xl:text-4xl w-fit">
                                <span class="main__title-first">BÔNUS DE ATÉ</span>
                                <span class="main__title-second font-bold"><strong>R$200</strong></span>
                                <!-- <span class="main__title-third">RUB</span> -->
                            </h6>
                        </div>
                        <div class="main__subtitle">
                            <h6 class="bg-secondary text-white py-3 px-5 m-0 md:text-3xl lg:text-4xl xl:text-4xl w-fit">
                                <span class="main__subtitle-first">PARA NOVOS CLIENTES</span>
                                <!-- <span class="main__subtitle-second font-bold">R$50</span> -->
                                <!-- <span class="main__subtitle-third">RUB</span> -->
                            </h6>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-x-5 items-center ganhe_box">
                        <div data-popup="registration" class="cursor-pointer btn btn-primary popup-btn popup-trigger" id="btng">CADASTRE-SE E GARANTA SEU BÔNUS</div>
                        <a href="https://stats.bets.com.br/bonus/" class="underline hover:no-underline" target="_blank">Condições do Bônus</a>
                    </div>
                </div>
                <div class="flex flex-wrap gap-5 text-white icon_txt_sec sm:text-black">
                    <div class="flex items-center gap-x-2">
                        <!-- <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg> -->
                        <img src="assets/img/icons8.png" alt="ic8" width="30">
                        <p class="text-white sm:text-black">Altas Probabilidades</p>
                    </div>
                    <div class="flex items-center gap-x-2">
                        <!-- <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg> -->
                        <img src="assets/img/networking-64.png" alt="ic8" width="30"/>
                        <p class="text-white sm:text-black">Jogue com segurança e confiança</p>
                    </div>
                    <div class="flex items-center gap-x-2">
                        <!-- <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg> -->
                        <img src="assets/img/coins-64.png" alt="ic8" width="30">
                        <p class="text-white sm:text-black">Pague com PIX</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="registration">
        <div class="modal-inner max-w-md w-full bg-white rounded overflow-hidden">
            <div class="modal-head bg-primary p-2 uppercase relative">
                <h5 class="m-0 text-white">cadastro</h5>
                <div class="popup-close absolute right-2 top-1/2 -translate-y-1/2 text-white cursor-pointer">
                    <!-- <img target="_blank" src="https://icons8.com/icon/82640/coin-in-hand"> -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>
            <div class="modal-body p-8">
                <div class="relative -mb-6 z-10">&nbsp;
                    <img src="assets/img/bets_black.png" alt="ball" class="inline-block"  style="margin-bottom: 29px;"/>
                </div>
                <div class="main__titles mb-10">
                    <div class="main__title">
                        <h4 class="text-white bg-primary py-2 px-3 xl:text-3xl m-0 w-fit mx-auto">
                        BÔNUS DE ATÉ
                            <span class="font-bold">R$200</span>
                        </h4>
                    </div>
                    <div class="main__subtitle">
                        <h5 class="bg-secondary text-white py-2 px-3 m-0 w-fit mx-auto">
                        PARA NOVOS CLIENTES
                            <!-- <span class="font-bold">R$50</span> -->
                        </h5>
                    </div>
                </div>

                <div class="">
                    <h6 class="mb-4">Verificação de número de celular</h6>
                    <style>
                        /* Chrome, Safari, Edge, Opera */
                        input::-webkit-outer-spin-button,
                        input::-webkit-inner-spin-button {
                        -webkit-appearance: none;
                        margin: 0;
                        }
                        /* Firefox */
                        input[type=number] {
                        -moz-appearance: textfield;
                        }
                        /* input {
                            text-align: left;
                        } */
                        ::-webkit-input-placeholder {
                            text-align: left;
                        }
                        :-moz-placeholder {
                            text-align: left;
                        }
                        /* .inputlbl {    
                            z-index: 99999 !important;
                            border-right: 1px solid #e2e2e2 !important;
                            width: 78px !important;
                            height: 36px !important;
                            margin-top: 10px !important;
                            margin-left: 35px !important;
                            padding-top: 10px !important;
                            vertical-align: middle !important;
                            text-align:center;
                        } */
                        .nameinput{ padding-left: 5px !important;
                                    border-top-left-radius: 0 !important;
                                    border-bottom-left-radius: 0 !important;
                                }
                        @media only screen and (min-device-width : 280px) and (max-device-width : 1024px) {
                            /* .inputlbl {    
                                z-index: 99999 !important;
                                border-right: 1px solid #e2e2e2 !important;
                                width: 55px !important;
                                height: 36px !important;
                                margin-top: 17px !important;
                                margin: 5px !important;
                                padding-top: 10px !important;
                                vertical-align: middle !important;
                                text-align:center !important;
                            } */
                            .nameinput{width:99% !important;margin-bottom:5px !important;padding-left: 5px !important;}
                            .phoneinput{width:99% !important;margin-bottom:5px !important;padding-left: 86px !important;}
                        }
                    </style>

                        <div class="" id="input_div">
                            <div class="inner_input_f">
                            <div class="relative number-field name_fil" style="z-index: 9999999999;">
                                <!-- <label for='name' class="inputlbl">NOME</label> -->
                                <span>NOME</span><input id="name" type="text" name="name" class="nameinput" autofocus placeholder="" autocomplete="off"/>
                            </div>
                            <div class="relative number-field" style="z-index: 9999999999;">
                                <input id="phone" type="tel" name="phone" maxlength="16" placeholder="<?php echo $phone_format;?>" class="phoneinput"/>
                                <input type="hidden" id="req_phone" value="<?php echo trim($phone); ?>"> 
                                <p id="phone-error" style="display:none;">
                                    <span style="color:red;font-size:12px;" id="phone-error-text"></span>
                                </p>
                            </div>
                    </div>
                            <button type="submit" name="submit" onclick="sendVerificationCode();" class="btn btn-primary rounded w-full uppercase mt-4 text-sm py-3">cadastro</button>
                        </div>
                        <div class="" id="verify_div" style="display:none;">
                            <div class="relative number-field" style="z-index: 9999999999;">
                                <input id="code" type="tel" name="code" class="form-control" placeholder="Digite o código de verificação" autocomplete="off"/>
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary rounded w-full uppercase mt-4 text-sm py-3" onclick="verifyCode();">
                            Verificar telefone
                            </button>
                        </div>
                        <p id="vcode-error" style="display:none;">
                            <span style="color:red;font-size:12px;" id="vcode-error-text"></span>
                        </p>         
                        <input type="hidden" id="req_phone" value="<?php echo trim($phone); ?>">              
                        <input type="hidden" id="currency_code" value="<?php echo trim($currency_code); ?>"/>            
                        <input type="hidden" id="req_name" value=""/>            
                    <h6 class="font-medium mt-4">já tem uma conta? <a href="https://pro.bets.com.br/" class="underline text-primary">Entrar</a></h6>
                    <div class="border-t border-gray-200 grid gap-y-3 pt-4" id="tnc_div_id">
                        <label class="text-xs flex items-center gap-x-2 text-black">
                            <input type="checkbox" class="w-4 h-4 border rounded" id="terms_conditions" checked="checked">
                            Eu concordo com <a href="https://stats.bets.com.br/bets-termos/" class="text-primary underline" target="_blank">termos e Condições</a>
                            <p id="tnc-error" style="display:none;text-align: left;">
                                <span style="color:red;font-size:12px;" id="tnc-error-text"></span>
                            </p>
                        </label>
                        
                        <label class="text-xs flex items-center gap-x-2 text-black">
                            <input type="checkbox" class="w-4 h-4 border rounded" checked="checked">
                            Receba newsletters sobre promoções por e-mail e sms
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        const popup_trigger = document.querySelector('.popup-trigger');
        const modal = document.querySelector('.modal');
        if(popup_trigger){
            popup_trigger.addEventListener('click',function(e){
                const trigger = e.target.getAttribute("data-popup");
                const modal_id = modal.getAttribute('id');
                if(trigger === modal_id){
                    modal.classList.add('active');
                    document.getElementById('name').focus();
                }
            });
        }

        const popup_close = document.querySelector('.popup-close');
        if(popup_close){
            popup_close.addEventListener('click', function(){
                modal.classList.remove('active');
            })
        }
    </script>

<link rel="stylesheet" href="./assets/css/intlTelInput.css"/>
    <script src="./assets/js/intlTelInput.min.js"></script>
    <script>
    var input = document.querySelector("#phone");
    window.intlTelInput(input, {
    // show dial codes too
        separateDialCode: true,
        // If there are some countries you want to show on the top.
        // here we are promoting russia and singapore.
        preferredCountries: ["br", "us"],
        //Default country
        initialCountry:"<?php echo $cc ; ?>"
    });

    function sendVerificationCode() {

        if(document.getElementById('phone').value != '' && document.getElementById('name').value != '' ) {
            document.getElementById('phone-error').style.display = 'none';
            var dial_code = (document.getElementsByClassName('iti__selected-dial-code')[0].innerHTML).trim();
            dial_code = dial_code.replace('+', '');
            var phone = dial_code+document.getElementById('phone').value;
            document.getElementById('req_name').value = document.getElementById('name').value;
            //document.getElementById('req_phone').value = document.getElementById('phone').value;

            var xhttp_ajax_req = new XMLHttpRequest();
            xhttp_ajax_req.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    if(this.responseText != 'error') {
                        document.getElementById('req_phone').value = this.responseText;
                        document.getElementById('verify_div').style.display = 'block'; 
                        document.getElementById('input_div').style.display = "none";
                        document.getElementById('tnc-error').style.display = 'none';
                        document.getElementById('tnc_div_id').style.display = 'none';
                    }
                    else {
                        document.getElementById('phone-error-text').innerHTML = 'Não foi possível enviar o código de verificação.';
                        document.getElementById('phone-error').style.display = 'block';
                    }
                }
            };
            xhttp_ajax_req.open("POST", "verify/ajax.php", false);
            xhttp_ajax_req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp_ajax_req.send("submit=yes&phone="+phone);
        }    
        else {
            document.getElementById('phone-error-text').innerHTML = 'Por favor, digite seu nome e número de telefone.';
            document.getElementById('phone-error').style.display = 'block';
            return false;
        }
        
    }

    function verifyCode() {
        if(document.getElementById('code').value != '') {
            var code = document.getElementById('code').value;
            var phone = document.getElementById('req_phone').value;
            var xhttp_ajax_req = new XMLHttpRequest();
            xhttp_ajax_req.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {

                    if(this.responseText == 'success') {
                        document.getElementById('verify_div').innerHTML = '&#9989;&nbsp;Número de telefone verificado.';
                        //alert('Debug Point 1');
                        callVerifiedApi();
                    } else {
                        document.getElementById('vcode-error-text').innerHTML = 'Código de verificação inválido.';
                        document.getElementById('vcode-error').style.display = 'block';
                    }
                }
            };
            xhttp_ajax_req.open("POST", "verify/ajax_verify.php", false);
            xhttp_ajax_req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp_ajax_req.send("submit=yes&code="+code+"&phone="+phone);
        }    
        else {
            document.getElementById('vcode-error-text').innerHTML = 'Insira o código de verificação.';
            document.getElementById('vcode-error').style.display = 'block';
        }
    }

    function callVerifiedApi() {
        //alert('Debug Point 2');
        var phone = document.getElementById('req_phone').value;
        var name = document.getElementById('req_name').value;
        //var currency_code = document.getElementById('currency_code').value;
        var currency_code = 'BRL';
        var xhttp_ajax_req = new XMLHttpRequest();
        xhttp_ajax_req.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //alert('Debug Point 3');
                //var responseObj = JSON.parse(this.responseText);
                let text = this.responseText;
                console.log('response', this);
                const responseArray = text.split("_BTS_");
                //alert(responseArray[0]);
                if(responseArray[0] == 'success') {
                    //location.href = 'https://stats.bets.com.br/success/';
                    sendSms(responseArray[1], responseArray[2], responseArray[3]);
                } else {
                    document.getElementById('vcode-error-text').innerHTML = this.responseText+'  ';
                    document.getElementById('vcode-error').style.display = 'block';
                }
                
            } else {
                //alert('Debug Point JS');
                document.getElementById('vcode-error-text').innerHTML = this.responseText+'  ';
                document.getElementById('vcode-error').style.display = 'block';
            }
        };
        xhttp_ajax_req.open("POST", "ajax_verified.php", false);
        xhttp_ajax_req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //xhttp_ajax_req.send("phone="+phone);
        xhttp_ajax_req.send("phone="+phone+"&name="+name+"&currency_code="+currency_code);
    }
 

    function sendSms(phone, username, password) {
        var xhttp_sms_req = new XMLHttpRequest();
        var req_name = document.getElementById('req_name').value;
        // var cur = document.getElementById('currency_code').value;
        var cur = 'BRL';
        var rfsn_id = localStorage.getItem('rfsn_aid');
        var rfsn_cs = localStorage.getItem('rfsn_cs');
        var rfsn_ci = localStorage.getItem('rfsn_ci');
        
        xhttp_sms_req.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {

                if(this.responseText == 'success') {
                    //location.href = 'https://stats.bets.com.br/success/';
                    var success_url = 'https://stats.bets.com.br/success/?u='+req_name+'&ph='+phone+'&usn='+username+'&cur='+cur+'&rfsn_id='+rfsn_id+'&rfsn_cs='+rfsn_cs+'&rfsn_ci='+rfsn_ci;
                    windowLocation(success_url);
                } else {
                    document.getElementById('vcode-error-text').innerHTML = 'Erro ao enviar usuário e senha.';
                    document.getElementById('vcode-error').style.display = 'block';
                }
                
            }
        };
        xhttp_sms_req.open("POST", "verify/sms.php", false);
        xhttp_sms_req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp_sms_req.send("phone="+phone+"&username="+username+"&password="+password);
    }

    function go_to_registration() {
        //location.href="https://cadastro.bets.com.br/novo";
        document.getElementById('vcode-error').style.display = 'none';
        document.getElementById('verify_div').style.display = 'none';
        document.getElementById('input_div').style.display = 'block';
    }

    function windowLocation(url){
        var X = setTimeout(function(){
            window.location.replace(url);
            return true;
        },300);

        if( window.location = url ){
            clearTimeout(X);
            return true;
        } else {
            if( window.location.href = url ){
                clearTimeout(X);
                return true;
            }else{
                clearTimeout(X);
                window.location.replace(url);
                return true;
            }
        }
        return false;
    };

    </script>
    <?php if($currency_code == 'EURO')  {?>

        <script>
        document.getElementById('phone').addEventListener('input', function (e) {
            var x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,4})(\d{0,4})/);
            e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
        });
        </script>

    <?php } else if($currency_code == 'BRL') {?>

        <script>
        document.getElementById('phone').addEventListener('input', function (e) {
            var x = e.target.value.replace(/\D/g, '').match(/(\d{0,2})(\d{0,5})(\d{0,4})/);
            e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
        });
        </script>
    
    <?php } else {?>

        <script>
        document.getElementById('phone').addEventListener('input', function (e) {
            var x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
            e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
        });
        </script>

    <?php }?>
</body>
</html>