<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailVerification extends Model
{
  use HasFactory;

  protected $table = 'email_verifications';
  public $timestamps = true;
  protected $fillable = [
    'euid',
    'user_id',
    'created_at',
  ];

  public function user() {
    return $this->belongsTo('App\Models\User');
  }
}
