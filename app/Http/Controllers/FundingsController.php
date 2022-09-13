<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\AccountFunding as Funding;

class FundingsController extends Controller
{
  private $user;

  public function __construct()
  {
      $this->middleware('auth:api');

      $this->user = auth('api')->user();
  }


  public function index()
  {
      $fundings = Funding::all()
      ->groupBy('type');

      return response()->json($fundings);
  }

  public function store(Request $request) {
    //do something with the rest of the data.. card number cvv exp exetra...
    
    $type = $request->input('type');
    $amount = $request->input('amount');

    $res = $this->user->fundings()->create([
      'type' => $type,
      'amount' => $amount,
      'status' => 'pending',
    ]);

    if($res)
      return response()->json(['status' => 'success']);
    else
      return response()->json(['status' => 'failed']);

  }
}
