<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $fillable = ['transaction_id', 'from', 'to', 'type', 'amount'];

    public function account_from()
    {
        return $this->belongsTo('App\Models\Account', 'account_from');
    }

    public function account_to()
    {
        return $this->belongsTo('App\Models\Account', 'account_to');
    }
}
