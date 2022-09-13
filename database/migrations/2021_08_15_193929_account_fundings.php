<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AccountFundings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
         Schema::create('account_fundings', function (Blueprint $table) {
             $table->id()->startingValue(198000);
             $table->unsignedBigInteger('user_id')->default(null);
             $table->enum('type', ['card', 'bank', 'crypto']);
             $table->double('amount');
             $table->enum('status', ['pending', 'declined', 'complete'])->default('pending');
             $table->timestamps();

             $table->foreign('user_id')->references('id')->on('users');
         });
     }

     /**
      * Reverse the migrations.
      *
      * @return void
      */
     public function down()
     {
         Schema::dropIfExists('account_fundings');
     }
}
