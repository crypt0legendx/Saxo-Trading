<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TwoStepAuth extends Model
{
    use HasFactory;

    protected $table = 'twostep_auth';
    public $timestamps = true;
    protected $fillable = [
      'code',
      'user_id',
      'created_at',
    ];

    public function user() {
      return $this->belongsTo('App\Models\User');
    }
}
