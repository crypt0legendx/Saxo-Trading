<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'id', 'account_id', 'stock_id', 'volume', 'open_price', 'close_price', 'type', 'trade_type', 'stop_loss', 'take_profit', 'updated_at'
    ];

    protected $hidden = [
        'account_id', 'stock_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\Account', 'account_id');
    }

    public function stock()
    {
        return $this->belongsTo('App\Models\Stock', 'stock_id');
    }

    public function pl()
    {
        $direction = 0;

        if($this->close_price != null && $this->close_price == $this->open_price)
          return 0;

        $base = ($this->close_price == null) ? $this->stock->price : $this->close_price;

        if($this->type == 'buy')
            $direction = $base - $this->open_price;
        else
            $direction = $this->open_price - $base;

        $data = round($this->volume * $this->stock->lot * $direction / $this->user->leverage, 2);

        return $data;
    }
}
