<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills_payment', function (Blueprint $table) {
            $table->bigIncrements('bills_payment_id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->bigInteger('biller_id')->unsigned();
            $table->foreign('biller_id')->references('biller_id')->on('biller_company');
            $table->string('subscriber_name')->length(50);
            $table->bigInteger('subscriber_no');
            $table->string('reference')->length(200);
            $table->double('amount')->length(20);
            $table->text('memo')->length(200)->nullable();
            $table->tinyInteger('status');
            $table->double('transaction_fee', 8)->length(20);
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
        Schema::dropIfExists('bills_payment');
    }
}
