<?php

namespace App\Http\Controllers;

use \JWTAuth;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationsController extends Controller
{

  private $user;
  private $account;

  public function __construct()
  {
      $this->middleware('auth:api');

      $this->user = auth('api')->user();
      $this->account = $this->user->accounts()->where('active', true)->first();
  }

  public function index() // return all notifs
  {
      $data = Notification::where('account_id', $this->account->id)
      ->get();


      return response()->json($data);
  }

  public function store(Request $request)
  {
    $notif = new Notification([
      'notification' => $request->get('notification'),
      'type' => $request->get('type'),
    ]);

    $this->account->notifications()->save($notif);
  }

  public function clear() {
      $this->account->notifications()->delete();
  }
}
