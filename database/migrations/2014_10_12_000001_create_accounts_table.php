<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id()->startingValue(218000);
            $table->unsignedBigInteger('user_id');
            $table->enum('type', ['demo', 'real']);
            $table->string('sub_type')->default('MT4 Fixed Spreads');
            $table->double('balance')->default(0);
            $table->double('leverage')->default(20);
            $table->string('currency')->default('USD');
            $table->string('token')->nullable();
            $table->string('time_zone')->default('+0');
            $table->string('sound')->default('Enabled');
            $table->boolean('active')->default(0);
            $table->json('alerts')->nullable()->default(null);
            $table->json('alerts_options')->nullable();
            $table->json('workspace')->nullable();
            $table->json('favourites')->nullable();
            $table->json('account_data')->nullable();

            // Relationships
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
        Schema::dropIfExists('accounts');
    }
}
