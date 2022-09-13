<?php
namespace App\Traits;
use Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;

use \JWTAuth;
use \Request;
use App\Models\User;

trait Mailgun {

  protected function sendMail($subject, $template, $user) {
    Mail::send([], [],
                function ($message) use ($template, $user, $subject) {
                   $message->to($user->email, $user->name)
                   ->from(Config::get('app.from_address'), Config::get('app.name'))
                   ->subject('noreply @ ' . Config::get('app.name') . ' | ' . $subject)
                   ->setBody($template, 'text/html');
      });
  }


  public function sendTwoStepCode($code) {
    $user = auth()->user();

    $template = Storage::get('templates/twofacode_template.html');
    $template = str_replace("{{twofa_code}}", $code, $template);
    $template = str_replace("{{name}}", $user->name, $template);

    $this->sendMail('2-Step Code Verification', $template, $user);
  }

  public function sendEmailVerificationMail($uemail_uid) {
    $user = auth()->user();

    $template = Storage::get('templates/email_verification.html');
    $email_link = Config::get('app.url') . '/api/auth/everify/' . $uemail_uid;
    $template = str_replace("{{email_link}}", $email_link, $template);
    $template = str_replace("{{name}}", $user->name, $template);

    $this->sendMail('Email Verification', $template, $user);
  }

  public function sendRecoveryPasswordMail($pruid, $user) {
    if(!$user) return;

    $template = Storage::get('templates/reset_password_email.html');
    $email_link = Config::get('app.url') . '/api/auth/password/recovery/' . $pruid;
    $template = str_replace("{{email_link}}", $email_link, $template);
    $template = str_replace("{{name}}", $user->name, $template);

    $this->sendMail('Reset Password', $template, $user);
  }

}

 ?>
