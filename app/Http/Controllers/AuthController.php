<?php

namespace App\Http\Controllers;

use \JWTAuth;
use \Validator;
use \Request;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Account;
use App\Models\EmailVerification as EVerify;
use App\Models\PasswordRecovery;

use Illuminate\Http\Request as Req;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use App\Traits\Mailgun;


class AuthController extends Controller
{
  use Mailgun;
  /**
  * Create a new AuthController instance.
  *
  * @return void
  */
  public function __construct()
  {
    $this->middleware('auth:api', ['except' => ['login', 'signup', 'checkTwoStep', 'verifyEmail', 'recover_password', 'verify_recover_password', 'reset_new_password' ]]);
  }

  /**
  * Get a JWT via given credentials.
  *
  * @return \Illuminate\Http\JsonResponse
  */

  public function verifyEmail($euid) {
    if(!$euid || strlen($euid) != 20) return response()->json(['error' => 'Invalid email verification code.'], 404);

    $res= EVerify::where('euid', $euid)->with("user")->first();
    if(!$res) return response()->json(['error' => 'Invalid email verification code.'], 404);
    if($res->created_at->timestamp + 300 < time())  return response()->json(['error' => 'Email verification code is expired'], 404);
    if($res->user->email_verified_at != null) return response()->json(['error' => 'Email verification code is expired'], 404);

    $res->user->email_verified_at = time();
    $res->user->save();
    return redirect('/');
  }

  public function sendEmailVerification() {
    $user = auth('api')->user();

    if($user->email_verified_at != null) return response()->json(['error' => 'Already verified']);

    $last = $user->emailVerification()->latest()->first();
    if($last)
      if($last->created_at->timestamp + 60 > time()) return response()->json(['error' => 'Please, wait before making another request']);

    $euid = Str::random(20);

    $user->emailVerification()->create([
      "euid" => $euid
    ]);

    $this->sendEmailVerificationMail($euid);

    return response()->json(['success' => 'Email verification sent successfully']);
  }

  public function login()
  {
    $credentials = request(['email', 'password']);

    if (! $token = auth('api')->attempt($credentials)) {
      $user = User::where('email', request('email'))->first();

      if($user) {
        $notif = new Notification([
          'notification' => 'Login attempt with wrong password.',
          'type' => 'security',
        ]);
        $user->accounts()->where('active', true)->first()->notifications()->save($notif);
      }
      return response()->json(['error' => 'Unauthorized'], 401);
    }

    return $this->respondWithToken($token);
  }
  public function update_password(Req $request) {
    $user = auth('api')->user();

    $current = $request->input("current_password");
    $new = $request->input("new_password");

    if(!Hash::check($current, $user->password))
    return response()->json(["status" => "ERROR", "reason" => "Wrong current password"]);

    $user->password = $new;
    $user->save();
    return response()->json(["status" => "SUCCESS", "reason" => "Successfully changed password"]);
  }

  public function recover_password(Req $request) {
    $email = $request->input('email');
    if(!$email) return response()->json(['error' => "This email address is invalid"]);

    $user = User::where('email', $email)->first();
    if(!$user) return response()->json(['error' => "Couldn't find account associated with this email address"]);

    $last = $user->passwordRecovery()->latest()->first();

    if($last)
      if($last->created_at->timestamp + 300 > time()) return response()->json(['error' => 'Please, wait before making another request']);

    $pruid = Str::random(100);

    $user->passwordRecovery()->create([
      'pruid' => $pruid
    ]);

    $this->sendRecoveryPasswordMail($pruid, $user);

    return response()->json(['success' => 'Successfully sent password reset mail']);

  }

  protected function validate_reset_password_link($pruid) {
    if(strlen($pruid) != 100) return json_encode(['error' => 'Invalid password reset link']);
    $res = PasswordRecovery::where('pruid', $pruid)->with('user')->first();
    if(!$res) return json_encode(['error' => 'Invalid password reset link']);
    if($res->created_at->timestamp + 300 < time())  return json_encode(['error' => 'This password reset link is expired']);

    return 1;
  }


  public function verify_recover_password($pruid) {
    $valid = $this->validate_reset_password_link($pruid);

    if($valid == 1)
      return redirect('/?pruid=' . $pruid);

    return response($valid, 404);
  }

  public function reset_new_password(Req $request, $pruid) {
    $valid = $this->validate_reset_password_link($pruid);

    if($valid != 1)
      return response($valid, 404);

    $password = $request->input('password');

    $res = PasswordRecovery::where('pruid', $pruid)->with('user')->first();
    $res->created_at = 0; //invalidate this link so it can never be used again

    $user = $res->user;
    if(Hash::check($password, $user->password))
      return response()->json(['error' => 'Your new password cannot be the same as your old password.']);

    $user->password = $password;
    $user->save();
    $res->save();
    return response()->json(['success' => 'Your password was changed successfully!']);
  }

  /**
  * Get the authenticated User.
  *
  * @return \Illuminate\Http\JsonResponse
  */
  public function me()
  {
    return response()->json(auth('api')->user());
  }

  /**
  * Log the user out (Invalidate the token).
  *
  * @return \Illuminate\Http\JsonResponse
  */
  public function logout()
  {
    auth('api')->logout();

    return response()->json(['message' => 'Successfully logged out']);
  }

  /**
  * Refresh a token.
  *
  * @return \Illuminate\Http\JsonResponse
  */
  public function refresh()
  {
    return $this->respondWithToken(auth('api')->refresh());
  }

  /**
  * Get the token array structure.
  *
  * @param  string $token
  *
  * @return \Illuminate\Http\JsonResponse
  */


  protected function respondWithToken($token)
  {
    $user = auth('api')->user();
    $user->token = $token;
    $user->save();

    if($user->{'2fa'} == true) {
      $code_code = str_pad(mt_rand(1,99999999),8,'0',STR_PAD_LEFT);
      $code = $user->twoStepCode()->create([
        'code' => $code_code
      ]);

      $this->sendTwoStepCode($code_code);

      return response()->json([
        'temp_token' => Crypt::encryptString($token),
        '2fa' => true,
      ]);
    }

    return response()->json([
      'access_token' => $token,
      'token_type' => 'bearer',
      'expires_in' => auth('api')->factory()->getTTL() * 60
    ]);
  }

  public function checkTwoStep(Req $request, $code) {
    $token = Crypt::decryptString($request->input('token'));
    $user = auth('api')->setToken($token)->user();

    $last_code = $user->twoStepCode()->latest()->first();

    if($last_code == null) return response()->json(['status' => 'ERROR', 'reason' => "The code you've entered is invalid."]);
    if($code != $last_code->code) return response()->json(['status' => 'ERROR', 'reason' => "The code you've entered is invalid."]);
    if($last_code->created_at->timestamp + 300 < time()) return response()->json(['status' => 'ERROR', 'reason' => "The code you've entered is expired."]);

    return response()->json([
      'access_token' => $user->token,
      'token_type' => 'bearer',
      'expires_in' => auth('api')->factory()->getTTL() * 60
    ]);
  }


  public function signup() {
    $vrs = Validator::make(request()->all(), [
      'first_name' => 'required|string|max:255',
      'last_name' =>  'required|string|max:255',
      'email' =>      'required|string|email|max:191|unique:users',
      'password' =>   'required|string|min:6',
      ])->validate();

      $name = request()->input('first_name') . ' ' . request()->input('last_name');
      $user = \App\Models\User::create([
        'name' => $name,
        'email' => request()->input('email'),
        'password' => request()->input('password')
      ]);

      $token = auth('api')->attempt(request(['email', 'password']));
      return $this->respondWithToken($token);
    }
  }
