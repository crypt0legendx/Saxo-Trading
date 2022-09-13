<?php

namespace App\Http\Controllers;

use \JWTAuth;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Stock;
use App\Models\Account;

class OrderController extends Controller
{

    private $user;
    private $account;

    public function __construct(Request $request)
    {
        $this->middleware('auth:api')->except('close');

        if(!$request->get('account_id'))
        {
            $this->user = auth('api')->user();
            $this->account = $this->user->accounts()->where('active', true)->first();
        }
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        if($this->account == null) return null;

        $data = Order::with('stock')
          ->where('account_id', $this->account->id)
          ->get();

        foreach($data as $order)
          $order->pl = $order->pl();

        $response = $data;

        return response()->json($response);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        // Inputs
        $account_eligible = true; //place holder, will be used to deny account's ability to trade

        $volume = $request->input('volume');
        $type = $request->input('type');
        $trade_type = strtolower($request->input('trade_type'));
        $duration = $request->input('duration');
        $rawType = $type == 'buy' ? 'Ask' : 'Bid';

        $sl = $request->input('sl');
        $tp = $request->input('tp');

        $sl = ($sl == 0) ? null : $sl;
        $tp = ($tp == 0) ? null : $tp;

        // Models
        $stock = Stock::where('api_name', $request->input('stock_id'))->first();

        //deny
        if(!$account_eligible)
          return response()->json(['error' => "Couldn't place " . $rawType . " on " . $stock->name . ". Reason: You are blocked from trading"]);

        // Balance Calculation
        $margin_required = ($volume * $stock->lot * $stock->price) / $this->account->leverage;

        if($this->account->margin_free() >= $margin_required)
        {
            // Create
            $order = Order::create([
                'account_id' => $this->account->id,
                'stock_id' => $stock->id,
                'volume' => $volume,
                'open_price' => ($trade_type != 'market') ? round($request->input('entryPrice'), 5) : $stock->price,
                'type' => $type,
                'trade_type' => strtolower($trade_type),
                'stop_loss' => $sl,
                'take_profit' => $tp,
                'updated_at' => ($duration == null) ? null : date("Y-m-d",strtotime($duration))
            ]);

            $data = $order->with('stock')->get()->last()->toJSON();
            return response($data);
        }
        else {

        return response()->json(['error' => "Couldn't place " . $rawType . " on " . $stock->name . ". Reason: Insufficient Balance"]);
      }
    }

    /**
    * Close user order
    *
    * @param  ID  $id
    * @param  App\Models\User  $user
    * @return App\Models\Order
    */
    public function closeOrder($id, $account)
    {
        // GET Order & Close it
        $order = Order::find($id);

        if($order->trade_type != 'market')
        {
          $order->close_price = $order->open_price;
          $order->save();
          return $order->toJSON();
        }

        $pl = $order->pl();
        $order->close_price = $order->stock->price;
        $order->save();

        // UPDATE User
        $account->balance += $pl;
        $account->save();

        $response = Order::with('stock')->where('id', $order->id)->first();
        $response->pl = $pl;

        return $response->toJSON();
    }

    public function update($id, Request $request) {
      $order = $this->account->orders->find($id);
      if(!$order) return -1; //cant find order

      $new = false;

      $volume = $request->input('volume');
      $type = $request->input('type');
      $sl = $request->input('sl');
      $tp = $request->input('tp');

      $sl = ($sl == 0) ? null : $sl;
      $tp = ($tp == 0) ? null : $tp;

      if($volume != $order->volume || $type != $order->type)
          $new = true;

      $stock = Stock::where('api_name', $request->input('stock')['api_name'])->first();

      $data = null;
      if($new) { //fuck the old order and create new with the same ID
        $this->close(null, $order->id);
        if($this->account->margin_free() >= ($volume * $stock->lot * $stock->price) / $this->account->leverage) { //if user has enough cash
          $res = $this->account->orders->find($id)->delete();

          $Norder = Order::create([
              'id' => $id,
              'account_id' => $this->account->id,
              'stock_id' => $stock->id,
              'volume' => $volume,
              'open_price' => $stock->price,
              'type' => $type,
              'stop_loss' => $sl,
              'take_profit' => $tp,
          ]);

          return response()->json(Order::where('id', $id)->with('stock')->get()->last());
        } else {
          //not enough balance, restore the order
          $order->close_price = null;
          $order->save();

          return -2; //not  enough balance
        }
      } else {
        if($sl != null)
          $order->stop_loss = $sl;

        if($tp != null)
          $order->take_profit = $tp;

          $order->save();
          return response($order->with('stock')->get()->last()->toJSON());
      }
    }


    public function close(Request $request = null, $id = null)
    {
        if(!$id)
        {
            $response = [];
            $orders = $this->account->orders->where('close_price', null);
            foreach($orders as $order)
              array_push($response, json_decode($this->closeOrder($order->id, $this->account)));

            return response()->json($response);
        }
        else
        {
            if($request == null) {
              return $this->closeOrder($id, $this->account);
            }

            $account_id = $request->get('account_id');

            if($account_id == null || $account_id == '')
                return $this->closeOrder($id, $this->account);
            else
                return $this->closeOrder($id, Account::find($account_id));
        }
    }
}
