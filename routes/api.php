<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\FundingsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//
// Authentication Controller
//

Route::group([
  'middleware' => 'api',
  'prefix' => 'auth'
], function ($router) {
  Route::get('everify/{euid}', [AuthController::class, 'verifyEmail']);

  Route::post('login', [AuthController::class, 'login'])->name('login');
  Route::post('logout', [AuthController::class, 'logout']);
  Route::post('signup', [AuthController::class, 'signup'])->name('signup');
  Route::post('refresh', [AuthController::class, 'refresh']);
  Route::post('password/recovery', [AuthController::class, 'recover_password']);
  Route::get( 'password/recovery/{pruid}', [AuthController::class, 'verify_recover_password']);
  Route::post('password/recovery/change/{pruid}', [AuthController::class, 'reset_new_password']);

  Route::post('twostep/{code}', [AuthController::class, 'checkTwoStep'])->name('checkTwoStep');
});

//
// User Controller
//

Route::group([
  'middleware' => 'api',
  'prefix' => 'user'
], function ($router) {
  //user
  Route::get('/', [UserController::class, 'index']); //retrieve user object
  Route::post('/password/update', [AuthController::class, 'update_password']);
  Route::post('/password/recover', [AuthController::class, 'recover_password']);
  Route::post('/everify/send', [AuthController::class, 'sendEmailVerification']);

  //wallet funds
  Route::post('/fund', [FundingsController::class, 'store']);
  Route::get('/transaction/history', [TransactionController::class, 'history']);

  //accounts
  Route::post('/account/{id}', [UserController::class, 'changeAccount']); //log another user account
  Route::post('/create/account', [UserController::class, 'user_addaccount']);
  Route::post('/accounts/{id}/addfunds', [UserController::class, 'accountAddFunds']);

  //user props
  Route::post('/update/account/{property}', [UserController::class, 'updateAccount']); //update account prop
  Route::post('/update/{property}', [UserController::class, 'update']); //update user prop
  Route::post('/kyc/update', [UserController::class, 'updateKYC']);

  //bank info
  Route::post('/bank/add', [UserController::class, 'bankadd']);
  Route::post('/bank/remove/{id}', [UserController::class, 'bankrm']);
  Route::post('/bank/update/{id}', [UserController::class, 'bankup']);


  //account info
  Route::get('/history', [UserController::class, 'exportHistory']);
  Route::get('/stats', [UserController::class, 'stats']);

  //notifications
  Route::get('/notifications/', [NotificationsController::class, 'index']);
  Route::post('/notifications/store', [NotificationsController::class, 'store']);
  Route::post('/notifications/clear', [NotificationsController::class, 'clear']);
});

//
// Order Controller
//

Route::group([
  'prefix' => 'orders'
], function ($router) {
    Route::group(['middleware' => 'api'], function ($router) {
        Route::post('/', [OrderController::class, 'index']);
        Route::post('/create', [OrderController::class, 'store']);
        Route::post('/edit/{id?}', [OrderController::class, 'update']);
    });
    Route::post('/close/{id?}', [OrderController::class, 'close'])->name('close_order');
});
