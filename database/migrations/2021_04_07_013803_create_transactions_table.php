<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id');
            $table->unsignedBigInteger('account_from')->default(null);
            $table->unsignedBigInteger('account_to')->default(null);
            $table->enum('type', ['deposit', 'withdraw', 'transfer']);
            $table->double('amount');
            $table->json('additional')->default(null);
            $table->enum('status', ['pending', 'declined', 'complete'])->default('pending');
            $table->timestamps();

            // Relationships
            $table->foreign('account_from')->references('id')->on('accounts');
            $table->foreign('account_to')->references('id')->on('accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
