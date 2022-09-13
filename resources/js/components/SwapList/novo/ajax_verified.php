<?php
// ini_set('display_errors', 1);
// error_reporting(-1);
require_once('send_email.php');

function CallAPI($method, $url, $data = false, $session_var = "")
{
    $curl = curl_init();
    //Use the CURLOPT_HTTPHEADER option to set the Content-Type
    //for the request.
    if($url=="https://api.mollybet.com/v1/sessions/"){
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
        ));
    }
    else{
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Session: '.$session_var
        ));
    }

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // Optional Authentication:
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, "username:password");

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}

$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$password =  substr(str_shuffle($permitted_chars), 0, 8).'b1';

$user_phone_number = trim($_REQUEST['phone']);

// $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
// $username =  substr(str_shuffle($permitted_chars), 0, 8);
$username =  "BETS".str_replace("+", "", $user_phone_number);


//$username =  $username.time();

$permitted_chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$name =  trim($_REQUEST['name']);
$currency_code =  trim($_REQUEST['currency_code']);
// $currency_code = 'BRL';

$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
$bets =  substr(str_shuffle($permitted_chars), 0, 2).'bets';

if($name == 'ziya') {
    $username =  "BETS".time();
}

$myJSON ='{
    "username": "'.$username.'",
    "password": "'.$password.'",
    "group_id":"1761", 
    "name":"'.$name.'",
    "ccy_code":"'.$currency_code.'",
    "roles":{"bet_placer":true,"trade_page_viewer":true, "customer":true}
}';

$loginJSON = '{"username":"XXXXXXXX","password":"XXXXXXXXXXXXX"}';
$response_login_api = $response = CallAPI("POST", "https://api.mollybet.com/v1/sessions/", $loginJSON);

$response_login_api = json_decode($response_login_api);
//send_email($username, $password, $user_phone_number);
//echo '<pre>';print_r($response_login_api);echo '</pre>';

try {

    if($response_login_api->status=="ok"){
        $session_var = $response_login_api->data;
        
        $response = CallAPI("POST", "https://api.mollybet.com/v1/customers/", $myJSON, $session_var);

        $response = json_decode($response);

         //echo '<pre>';print_r($response);echo '</pre>';
         //echo '<br/>'.$response->status.'<br/>';
         //echo '<br/>'.$response->code.'<br/>';
         //echo '<br/>'.$response->data->validation_errors->username[0].'<br/>';

        if($response->status=="ok"){

            send_email($username, $password, $user_phone_number, $name, $currency_code);
            ob_start();
            //echo json_encode(array_reverse(array('status'=> 'success','phone'=>$user_phone_number, 'username'=>$username, 'password'=>$password)));
            echo 'success_BTS_'.$user_phone_number.'_BTS_'.$username.'_BTS_'.$password;
            exit();

        }
        else{
            if($response->data->validation_errors->username[0]=='username_taken') {
                echo 'Lamento, mas esse número já foi utilizado para criar uma conta.<br/>Por favor, registre-se novamente utilizando outro número.<br/><button type="button" name="button" onclick="go_to_registration();" class="btn btn-primary rounded w-full uppercase mt-4 text-sm py-3">Cadastre-se novamente</button>';
            } else {
                echo 'Erro no cadastro. Por favor tente novamente depois de algum tempo.';
            }
            
            //echo "There has been an error.<br/>". $response->code ."<br> Can you please correct the information & try again ? <a href='https://cadastro.bets.com.br/novo/'>Go to Registration page.</a>";
            //$_GET['error'] = $response->code;
            //echo 'API Validation Error';
            @mail("ziyaindia78@gmail.com", "Mollybet customers API not working","Mollybet customers API not working Response - ".$response);
            exit();
        }
    } 
    else {
        echo 'Falha na autenticação. Por favor tente novamente depois de algum tempo.';
        //echo "There has been some error on server side: ". $response_login_api->code .".<br> Request you to please try after sometime.<a href='https://cadastro.bets.com.br/novo/'>Go to Registration page.</a>";
        //mail("markneal1@outlook.com", "Mollybet Credetials not working","Your Mollybet Login Credetials are not working https://www.bets.com.br/.");
        //mail("khan.mohammadazhar@gmail.com", "Mollybet Credetials not working","Your Mollybet Login Credetials are not working https://www.bets.com.br/.");
        @mail("ziyaindia78@gmail.com", "Mollybet Credetials not working", "Your Mollybet Login Credetials are not working https://www.bets.com.br/.");
        //echo 'API Validation Error';
        exit();
    }

}
catch(Exception $e) {
    //echo json_encode($e);
    @mail("ziyaindia78@gmail.com", "Cadestro SMS Error in catch block file - ajax_verified.php", json_encode($e));
    echo 'Erro ao cadastrar cliente. Por favor tente novamente depois de algum tempo.';exit();
}    
?>
