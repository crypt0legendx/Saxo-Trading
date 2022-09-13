<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="d-flex min-vh-100 justify-content-center align-items-center">
        <div class="shadow-sm mx-2 border rounded p-5"  id="input_div">
            <h3>Welcome back, user!</h3>
            <form action="" method="POST" onsubmit="return false;">
                <div class="form-group">
                    <label for="phone" class="mt-3 mb-1 text-muted">
                        Enter your mobile number:
                    </label>
                    <input id="phone" type="text" name="phone" class="form-control" required>
                    <button type="button" name="submit" class="btn btn-dark mt-2 w-100" onclick="sendVerificationCode();">Send Verification Code</button>
                </div>
            </form>
        </div>

        <div class="shadow-sm mx-2 border rounded p-5" id="verify_div" style="display:none;">
            <h3>Complete verification!</h3>
            <?php if (isset($alert)) { ?>
                <div class="alert alert-success mt-4"><?php echo $alert; ?></div>
            <?php } ?>
            <form action="" method="POST" onsubmit="return false;">
                <div class="form-group">
                    <label for="code" class="mt-3 mb-1 text-muted">
                        A verification code has been sent to
                        <b><?php echo $phone; ?></b>, <br />
                        enter the code below to continue.
                    </label>
                    <input id="code" type="text" name="code" class="form-control" required />
                    <input type="hidden" id="req_phone" value="<?php echo trim($phone); ?>">
                    <button type="button" name="submit" class="btn btn-success mt-2 w-100" onclick="verifyCode();">
                        Submit
                    </button>
                </div>
            </form>
        </div>

    </div>

</body>
<link rel="stylesheet" href="https://cdn.tutorialjinni.com/intl-tel-input/17.0.8/css/intlTelInput.css"/>
<script src="https://cdn.tutorialjinni.com/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
<script>
var input = document.querySelector("#phone");
window.intlTelInput(input, {
  // show dial codes too
    separateDialCode: true,
    // If there are some countries you want to show on the top.
    // here we are promoting russia and singapore.
    preferredCountries: ["br", "us"],
    //Default country
    initialCountry:"br"
});

function sendVerificationCode() {
    var dial_code = (document.getElementsByClassName('iti__selected-dial-code')[0].innerHTML).trim();
    dial_code = dial_code.replace('+', '');
    var phone = dial_code+document.getElementById('phone').value;
    var xhttp_ajax_req = new XMLHttpRequest();
	xhttp_ajax_req.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {

			document.getElementById('req_phone').value = this.responseText;
            document.getElementById('verify_div').style.display = 'block'; 
            document.getElementById('input_div').style.display = "none";
		}
	};
	xhttp_ajax_req.open("POST", "ajax.php", true);
	xhttp_ajax_req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp_ajax_req.send("submit=yes&phone="+phone);
}

function verifyCode() {
    var code = document.getElementById('code').value;
    var phone = document.getElementById('req_phone').value;
    var xhttp_ajax_req = new XMLHttpRequest();
	xhttp_ajax_req.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {

			if(this.responseText == 'success') {
                alert('Phone number verified');
            } else {
                alert('Invalid verification code');
            }
            
		}
	};
	xhttp_ajax_req.open("POST", "ajax_verify.php", true);
	xhttp_ajax_req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp_ajax_req.send("submit=yes&code="+code+"&phone="+phone);
}
</script>
</html>