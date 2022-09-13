<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin'], function () {
  Voyager::routes();
});

Route::any('{all?}', function(){
  return view('main');
})
->where('all', '^(?!api).*$');