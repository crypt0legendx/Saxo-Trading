<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordRecovery extends Model
{
  use HasFactory;

  protected $table = 'password_recoveries';
  public $timestamps = true;
  protected $fillable = [
    'pruid',
    'user_id',
    'created_at',
  ];

  public function user() {
    return $this->belongsTo('App\Models\User');
  }
}
