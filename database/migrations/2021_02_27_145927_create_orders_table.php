<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id')->default(null);
            $table->unsignedBigInteger('stock_id')->default(null);
            $table->double('volume');
            $table->double('open_price');
            $table->double('close_price')->nullable()->default(null);
            $table->double('stop_loss')->nullable()->default(null);
            $table->double('take_profit')->nullable()->default(null);
            $table->enum('type', ['buy', 'sell']);
            $table->enum('trade_type', ['market', 'limit', 'stop']);
            $table->timestamps();

            // Relationships
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->foreign('stock_id')->references('id')->on('stocks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
