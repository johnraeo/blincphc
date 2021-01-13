<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashInOutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_in_out', function (Blueprint $table) {
            $table->bigIncrements('cash_in_out_id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->bigInteger('method_id')->unsigned();
            $table->foreign('method_id')->references('method_id')->on('cash_transfer_method');
            $table->double('amount', 8)->length(20);
            $table->double('transaction_fee')->length(20);
            $table->string('reference')->length(200);
            $table->string('transact_type')->length(2);
            $table->tinyInteger('status');
            $table->timestamps();
            $table->dateTime('expired_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.  
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_in_out');
    }
}
