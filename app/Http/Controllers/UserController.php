<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Account;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserController extends Controller
{

  private $account;

  public function __construct()
  {
    $this->middleware('auth:api');
    $user = auth('api')->user();
    $this->account = $user->accounts()->where('active', true)->first();
  }

  public function index()
  {
    $user = auth('api')->user();
    $account = $user->accounts()->where('active', true)->first();
    if(!$account)
    $account = new Account;

    $account->user = $user;
    $account->accounts = $user->accounts()->get();
    $account->email = $user->email;
    $account->user_data = $user->user_data;
    $account->pip = 30;
    $account->kyc = $user->kyc;

    return response()->json($account);
  }

  public function bankadd(Request $request) {
      $user = auth('api')->user();
      $data = json_decode($user->user_data, true);

      $shiftid = 0;

      if(!isset($data['banks']) || count($data['banks']) == 0) {
        $data['banks'] = array();
      } else {
        $shiftid = intval(end($data['banks'])['id']) + 1;
      }

      $newbank = $request->input('bank');
      $newbank['id'] = $shiftid;

      array_push($data['banks'], $newbank);

      $user->user_data = json_encode($data);
      $user->save();

      return response()->json($newbank);
  }

  public function bankrm(Request $request, $bankid) {
    $user = auth('api')->user();
    $data = json_decode($user->user_data, true);
    if($data['banks'] == null)
      return response()->json(['status' => 'Error'], 404);

    $i = 0;
    foreach($data['banks'] as $bank) {
      if($bank['id'] == $bankid)
        array_splice($data['banks'], $i, 1);

      $i++;
    }

    $user->user_data = json_encode($data);
    $user->save();

    return response()->json($user->user_data);
  }


  public function bankup(Request $request, $bankid) {
    $user = auth('api')->user();
    $data = json_decode($user->user_data, true);
    if($data['banks'] == null)
      return response()->json(['status' => 'Error'], 404);

    $newval = $request->input('val');

    $i = 0;
    foreach($data['banks'] as $bank) {
      if($bank['id'] == $bankid)
        $data['banks'][$i] = $newval;

      $i++;
    }

    $user->user_data = json_encode($data);
    $user->save();

    return response()->json($user->user_data);
  }

  public function update(Request $request, $property)
  {
    $user = auth('api')->user();
	  $getStarted = $request->input('gs');

    if($property == "user_data" && $getStarted == null) {
      $user_data = json_decode($user->user_data, true);

      if($request->input('name') != null)
      $user->name = $request->input('name');

      if($request->input('nationality')  != null)
      $user_data['personalData']['nationality'] = $request->input('nationality');

      if($request->input('mobilePhone')  != null)
      $user_data['personalData']['mobilePhone'] = $request->input('mobilePhone');

      if($request->input('streetNumber')  != null)
      $user_data['residence']['streetNumber'] = $request->input('streetNumber');

      if($request->input('postalCode')  != null)
      $user_data['residence']['postalCode'] = $request->input('postalCode');

      if($request->input('city')  != null)
      $user_data['residence']['city'] = $request->input('city');

      if($request->input('status')  != null)
      $user_data['employment']['status'] = $request->input('status');

      if($request->input('industry')  != null)
      $user_data['employment']['industry'] = $request->input('industry');

      if($request->input('industryPos')  != null)
      $user_data['employment']['industryPos'] = $request->input('industryPos');

      if($request->input('education')  != null)
      $user_data['employment']['education'] = $request->input('education');

      if($request->input('anualIncome')  != null )
      $user_data['financial']['anualIncome'] = $request->input('anualIncome');

      if($request->input('estimatedNetWorth')  != null)
      $user_data['financial']['estimatedNetWorth'] = $request->input('estimatedNetWorth');

      if($request->input('sourceWealth')  != null)
      $user_data['financial']['sourceWealth'] = $request->input('sourceWealth');

      if($request->input('expectedCountryOfOriginOfFunds')  != null)
      $user_data['financial']['expectedCountryOfOriginOfFunds'] = $request->input('expectedCountryOfOriginOfFunds');

      if($request->input('expectedFunds') != null)
      $user_data['financial']['expectedFunds'] = $request->input('expectedFunds');


      $user_data = json_encode($user_data);

      $user->user_data = $user_data;

      $user->save();
      return response()->json($user);
    }

    $value = $request->input('value');
    $user->$property = $value;

    $user->save();

    return response()->json($user);
  }

  public function updateAccount(Request $request, $property)
  {
    $user = auth('api')->user();
    $account = $request->input('id');
    $value = $request->input('value');

    if($account)
    $account = $user->accounts()->find($account);
    else
    $account = $user->accounts()->where('active', true)->first();

    $account->$property = $value;

    $account->save();
    $user->accounts()->save($account);

    return response()->json($account);
  }

  public function accountAddFunds(Request $request, $id) {
    $user = auth('api')->user();
    $value = $request->input('value');

    $account = $user->accounts()->find($id);
    if(!$account)
    return response()->json('No account found');

    if($user->balance < $value)
    return response()->json('No funds available');

    $account->balance += $value;
    $user->balance -= $value;

    if($user->balance < 0)
    return response()->json('No funds available');

    $user->save();
    $account->save();

    return response()->json($account);
  }

  public function user_addaccount(Request $request) {
    $user = auth('api')->user();
    $type = $request->get('type');
    $leverage = $request->get('leverage');
    $currency = $request->get('currency');
    $balance = $request->get('balance');

    if(is_nan($balance)) return response()->json("Invalid request");
    $sub_type = 'DEMO';

    if($type != 'demo') {
      $sub_type = $type;
      $type = 'real';

      if($user->balance < $balance) return response()->json('Not enough balance');

      $user->balance -= $balance;
    }


    $acc = new Account(['type' => $type,
    'sub_type' => $sub_type,
    'balance' => $balance,
    'leverage' => $leverage,
    'currency' => $currency,
  ]);
  $acc->active = true;

  $rs = $user->accounts()->save($acc);
  $this->changeAccount($acc->id);

  if($rs) {
    $user->save();
    return $acc;
  }
}

public function updateKYC(Request $request) {
  $user = auth('api')->user();
  if($user->kyc != null) return response(null, 400);

  $obj = array('verified' => false);

  if($request->get('at') == 'passport') {
    if(!$request->file('front')->isValid()) return response(null,400);
    $front = Storage::putFile('kyc', $request->file('front'));

    $obj['method'] = $request->get('at');
    $obj['front'] = $front;

    $user->kyc = json_encode($obj);
    $user->save();

    return $front != NULL ? response(null,200) : response(null,400);
  } else if($request->get('at') == 'drivinglicense') {
    if(!$request->file('front')->isValid()
    && !$request->file('back')->isValid()) return response(null,400);

    $front = Storage::putFile('kyc', $request->file('front'));
    $back = Storage::putFile('kyc', $request->file('back'));

    $obj['method'] = $request->get('at');
    $obj['front'] = $front;
    $obj['back'] = $back;

    $user->kyc = json_encode($obj);
    $user->save();

    return $front != NULL && $back != NULL ? response(null,200) : response(null,400);
  } else if($request->get('at') == 'id') {
    if(!$request->file('front')->isValid()
    && !$request->file('back')->isValid()) return response(null,400);

    $front = Storage::putFile('kyc', $request->file('front'));
    $back = Storage::putFile('kyc', $request->file('back'));

    $obj['method'] = $request->get('at');
    $obj['front'] = $front;
    $obj['back'] = $back;

    $user->kyc = json_encode($obj);
    $user->save();

    return $front != NULL && $back != NULL ? response(null,200) : response(null,400);
  }

  return response(null,500);
}

public function changeAccount($id)
{
  if(is_nan($id)) return response()->json('Invalid Account ID');

  $user = auth('api')->user();
  $accounts = $user->accounts()->update(['active' => false]);

  $id = intval($id);
  $account = $user->accounts()->find($id);

  if($account) {
    $account->active = true;
    $account->save();
  }

  return $account;
}

public function exportHistory(Request $request)
{
  // File Name
  $fileName = 'history.csv';

  // Orders
  $orders = $this->account->orders()->where('close_price', '!=', null)->get();

  $headers = array(
    "Content-type"        => "text/csv",
    "Content-Disposition" => "attachment; filename=$fileName",
    "Pragma"              => "no-cache",
    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
    "Expires"             => "0"
  );

  $columns = array(
    'Create Time', 'Transaction', 'Volume', 'Instrument', 'Open Price', 'Stop Loss', 'Take Profit',
    'Close Time', 'Close Price', 'Pips', 'Swap', 'Gross Amount', 'Net Amount',
    'Balance', 'Credit', 'Commissions');

    $callback = function() use($orders, $columns) {
      $file = fopen('php://output', 'w');
      fputcsv($file, $columns);

      foreach ($orders as $order) {
        $row['Create Time']  = $order->created_at;
        $row['Transaction']  = $order->type;
        $row['Instrument']  = $order->stock->name;
        $row['Open Price']  = $order->open_price;
        $row['Stop Loss']  = ($order->stop_loss != null) ? $order->stop_loss : '-';
        $row['Take Profit']  = ($order->take_profit != null) ? $order->take_profit : '-';
        $row['Close Time']  = $order->updated_at;
        $row['Close Price']  = $order->close_price;
        $row['Pips']  = 1;
        $row['Swap']  = 1;
        $row['Gross Amount']  = $order->pl();
        $row['Net Amount']  = $order->pl();
        $row['Balance']  = 1;
        $row['Credit']  = 0;
        $row['Commissions']  = 0;

        fputcsv($file, $row);
      }

      fclose($file);
    };

    return response()->stream($callback, 200, $headers);
  }

  public function stats(Request $request)
  {
    $response = [];
    $periods = [1, 7, 31, 365, -1];
    foreach($periods as $period)
      $response[$period] = $this->fetchStats($request, $period);

    return $response;
  }

  private function fetchStats(Request $request, $daysCount)
  {
    $response = [ 'total' => [ 'profit' => 0, 'loss' => 0, 'pl' => 0 ], 'sum' => [ 'profit' => 0, 'loss' => 0 ], 'pl' => [ 'profit' => 0, 'loss' => 0 ], 'rate' => [ 'buy' => 0, 'sell' => 0 ], 'chart' => [ 'dates' => null, 'data' => null ] ];
    // All Time Check
    if($daysCount == -1)
      $orders = $this->account->orders;
    else
      $orders = $this->account->orders()->where('created_at', '>=', Carbon::now()->subDays($daysCount))->get();

    if($orders->count() == 0)
      return [];

    // Widget Data
    foreach($orders as $order)
    {
      $value = round($order->open_price * $order->volume * $order->stock->lot / $this->account->leverage, 2);

      $response['total'][($order->pl() > 0) ? 'profit' : 'loss'] += 1;
      $response['total']['pl'] += round($order->pl(), 2);
      $response['sum'][($order->pl() > 0) ? 'profit' : 'loss'] += $value;
      $response['pl'][($order->pl() > 0) ? 'profit' : 'loss'] += round($order->pl(), 2);

      if($order->pl() > 0)
        $response['rate'][$order->type] += 1;
    }

    $response['pl']['profit'] = round($response['pl']['profit'] / $response['total']['profit'], 2);
    $response['pl']['loss'] = round($response['pl']['loss'] / $response['total']['loss'], 2);

    // Chart Data
    $dateArray = []; $orderProfitArray = [];
    $dates = $orders->groupBy(function ($date) { return Carbon::parse($date->created_at)->format('Y-m-d'); });
    foreach($dates as $date => $group)
    {
      $pl = 0;
      $dateArray[] = $date;

      foreach($group as $order)
        $pl += round($order->pl(), 2);

      $orderProfitArray[] = $pl;
    }

    $response['chart']['dates'] = $dateArray;
    $response['chart']['data'] = $orderProfitArray;

    return $response;
  }
}
