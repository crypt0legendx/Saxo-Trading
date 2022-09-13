<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountFunding extends Model
{
    use HasFactory;

    protected $table = 'account_fundings';
    protected $fillable = ['user_id', 'type', 'amount', 'status'];

    public function from()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
