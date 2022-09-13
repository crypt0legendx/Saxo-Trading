<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends \TCG\Voyager\Models\User implements JWTSubject
{
  use HasFactory, Notifiable;

  protected $table = 'users';

  /**
  * The attributes that are mass assignable.
  *
  * @var array
  */
  protected $fillable = [
    'name',
    'email',
    'password',
    'balance',
    'email_verified_at',
  ];

  /**
  * The attributes that should be hidden for arrays.
  *
  * @var array
  */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
  * The attributes that should be cast to native types.
  *
  * @var array
  */


  public function passwordRecovery() {
    return $this->hasMany('App\Models\PasswordRecovery');
  }

  public function twoStepCode() {
    return $this->hasMany('App\Models\TwoStepAuth');
  }

  public function emailVerification() {
    return $this->hasMany('App\Models\EmailVerification');
  }

  public function fundings() {
      return $this->hasMany('App\Models\AccountFunding');
  }

  public function accounts()
  {
      return $this->hasMany('App\Models\Account');
  }

  // JWT
  public function setPasswordAttribute($password)
  {
    $this->attributes['password'] = bcrypt($password);
  }

  public function getJWTIdentifier()
  {
    return $this->getKey();
  }

  public function getJWTCustomClaims()
  {
    return [];
  }
}
