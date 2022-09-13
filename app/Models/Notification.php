<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';
    protected $fillable = [
      
        'account_id', 'notification', 'type'
    ];

    protected $hidden = [
        'account_id'
    ];

    public function account()
    {
        return $this->belongsTo('App\Models\Account');
    }


}
