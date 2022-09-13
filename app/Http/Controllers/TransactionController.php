<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

// Models
use App\Models\Account;
use App\Models\Transaction;
use App\Models\AccountFunding as Funding;

class TransactionController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function history() {
      $funds = Funding::all();
      $transfers = Transaction::with('account_from')->with('account_to')->get();
      $withdrawals = $funds;

      $data = array_merge($funds->toArray(), $transfers->toArray(), $withdrawals->toArray());


      return response()->json($data);
    }

    public function index()
    {
        $transactions = Transaction::all()
        ->groupBy('type');

        return response()->json($transactions);
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        //
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $type = $request->input('type');
        $amount = $request->input('amount');

        if($type == 'transfer')
        {
            $from = Account::find($request->input('from'));
            $to = Account::find($request->input('to'));

            if(!$from->type($to))
                return response('Different type of accounts (demo/real)', 406);

            if($from->balance - $amount < 0)
                return response('Amount greater than balance', 406);

            $transaction = new Transaction;
            $transaction->transaction_id = genTransactionCode();
            $transaction->account_from = $from->id;
            $transaction->account_to = $to->id;
            $transaction->type = 'transfer';
            $transaction->status = 'complete';
            $transaction->amount = $amount;
            $transaction->save();

            $from->balance -= $amount;
            $to->balance += $amount;

            return response('complete', 200);
        }
        else if($type == 'withdraw')
        {
            $user = auth('api')->user();
            $method = $request->input('method');
            $additional = $request->input('additional');

            if($user->balance - $amount < 0)
                return response('Amount greater than balance', 406);

            $transaction = new Transaction;
            $transaction->transaction_id = genTransactionCode();
            $transaction->account_from = $user->id;
            $transaction->account_to = null;
            $transaction->type = 'withdraw';
            $transaction->amount = $amount;
            $transaction->additional = json_encode($additional);

            $transaction->save();
        }
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        //
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function edit($id)
    {
        //
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {
        //
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        //
    }

    private function genTransactionCode()
    {
        $rand = rand(1000000, 9999999);
        $code = 'TXN' . $rand;

        if(Transaction::where('transaction_id', $code)->exists())
        return genTransactionCode();

        return $code;
    }
}
