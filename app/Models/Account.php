<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $table = 'accounts';
    protected $attributes = [
        'workspace' => '[{"id":0,"name":"Default Workspace","layout":{"type":"container","orientation":"horizontal","first":{"size":50,"el":{"id":4,"type":"cell","color":{"component":"Favourites","additional":{"data":"Favourites"}}}},"second":{"size":50,"el":{"type":"container","orientation":"vertical","first":{"size":50,"el":{"id":1,"type":"cell","color":{"component":"Chart","additional":{"symbol":"AAPL"}}}},"second":{"size":50,"el":{"id":0,"type":"cell","color":{"component":"Chart","additional":{"symbol":"EURUSD"}}}}}}}},{"id":1,"name":"Advanced","layout":{"type":"container","orientation":"horizontal","first":{"size":50,"el":{"type":"container","orientation":"vertical","first":{"size":50,"el":{"type":"container","orientation":"horizontal","first":{"size":50,"el":{"id":7,"type":"cell","color":{"component":"ClientPositions"}}},"second":{"size":50,"el":{"id":2,"type":"cell","color":{"component":"Chart","additional":{"symbol":"EURCAD"}}}}}},"second":{"size":50,"el":{"id":6,"type":"cell","color":{"component":"Instruments"}}}}},"second":{"size":50,"el":{"type":"container","orientation":"vertical","first":{"size":50,"el":{"id":4,"type":"cell","color":{"component":"History"}}},"second":{"size":50,"el":{"id":5,"type":"cell","color":{"component":"Orders"}}}}}}},{"id":2,"name":"Chart Grid","layout":{"type":"container","orientation":"horizontal","first":{"size":50,"el":{"type":"container","orientation":"vertical","first":{"size":50,"el":{"id":8,"type":"cell","color":{"component":"Chart","additional":{"symbol":"EURUSD"}}}},"second":{"size":50,"el":{"id":10,"type":"cell","color":{"component":"Chart","additional":{"symbol":"USDJPY"}}}}}},"second":{"size":50,"el":{"type":"container","orientation":"vertical","first":{"size":50,"el":{"id":9,"type":"cell","color":{"component":"Chart","additional":{"symbol":"AAPL"}}}},"second":{"size":50,"el":{"id":11,"type":"cell","color":{"component":"Chart","additional":{"symbol":"NZDUSD"}}}}}}}}]',
        'alerts_options' => '[{"text":"Stop Loss","value":false},{"text":"Take Profit","value":false},{"text":"Pending Order Filled","value":false},{"text":"Stop Out","value":false}]',
    ];

    //protected $appends = ['portfolio'];

    public $timestamps = false;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'balance',
        'sub_type',
        'type',
        'currency',
        'leverage',
        'token',
        'workspace',
        'user_data',
        'kyc',
    ];

    public function notifications()
    {
        return $this->hasMany('App\Models\Notification');
    }

    public function type($account)
    {
        return $this->type === $account->type ? true : false;
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    public function balance()
    {
        return $this->balance;
    }

    public function equity()
    {
        return $this->balance() + $this->pl();
    }

    public function pl()
    {
        $data = 0;
        $orders = $this->orders;
        foreach($orders as $order)
          $data += $order->pl();

        return $data;
    }

    public function margin_used()
    {
        $data = 0;
        $orders = $this->orders;
        foreach($orders as $order)
        {
          if($order->close_price != null)
            continue;

          $data += $order->volume * $order->open_price * $order->stock->lot / $this->leverage;
        }

        return round($data, 2);
    }

    public function margin_free()
    {
        return $this->equity() - $this->margin_used();
    }

    public function getPortfolioAttribute()
    {
        // Data Structure
        $data = (object) [
            'profit_num' => 0,
            'profit_sum' => 0,
            'loss_num' => 0,
            'loss_sum' => 0,
            'avg_profit' => 0,
            'avg_loss' => 0
        ];

        // GET User Orders
        $orders = $this->orders;

        // User Trades
        $data->total_trades = $orders->count();

        // P/L Trades Count
        foreach($orders as $order)
        {
            if($order->pl() > 0)
            {
                $data->profit_num += 1;
                $data->profit_sum += $order->pl();
            }
            else
            {
                $data->loss_num += 1;
                $data->loss_sum += $order->pl();
            }
        }

        $data->avg_profit = ($data->profit_num != 0) ? $data->profit_sum / $data->profit_num : $data->profit_sum;
        $data->avg_loss = ($data->loss_num != 0) ? $data->loss_sum / $data->loss_num : $data->loss_sum;

        return $data;
    }
}
