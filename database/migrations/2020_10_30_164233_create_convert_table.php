<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConvertTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('convert', function (Blueprint $table) {
            $table->bigIncrements('convert_id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->string('account_name')->length(65)->nullable();
            $table->double('amount_to_convert', 8)->length(20);
            $table->double('amount_converted', 8)->length(20);
            $table->tinyInteger('convert_from');
            $table->tinyInteger('convert_to');
            $table->double('exchange_rate', 8)->length(20);
            $table->tinyInteger('status');
            $table->double('transaction_fee', 8)->length(20);
            $table->string('trx_id')->length(60);
            $table->string('block_no')->length(60);
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
        Schema::dropIfExists('convert');
    }
}
