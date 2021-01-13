<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSendReqTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('send_req', function (Blueprint $table) {
            $table->bigIncrements('send_req_id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->integer('receiver_user_id')->unsigned();
            $table->foreign('receiver_user_id')->references('user_id')->on('users');
            $table->tinyInteger('currency_type');
            $table->string('transact_type')->length(2);
            $table->double('amount', 8)->length(20);
            $table->tinyInteger('status');
            $table->text('memo')->length(200)->nullable();
            $table->double('transaction_fee')->length(20);
            $table->bigInteger('trx_id');
            $table->bigInteger('block_no');
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
        Schema::dropIfExists('send_req');
    }
}
