<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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
            $table->integer('amount');
            $table->string('payment_method');
            $table->integer('paid_amount')->default(0);
            $table->boolean('async')->default(true);
            $table->boolean('capture')->default(true);
            $table->integer('captured_amount')->default(0);
            $table->integer('installments')->default(1);
            $table->string('ref_id');
            $table->string('status');
            $table->integer('card_id');
            $table->foreign('card_id')->references('card_id')->on('cards');
            $table->timestamps();
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
};
