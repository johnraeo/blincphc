<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashTransferMethodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_transfer_method', function (Blueprint $table) {
            $table->bigIncrements('method_id');
            $table->string('method_type')->length(50);
            $table->bigInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('company_id')->on('company');
            $table->tinyInteger('cash_in_support');
            $table->tinyInteger('cash_out_support');
            $table->tinyInteger('cash_in_availability');
            $table->tinyInteger('cash_out_availability');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_transfer_method');
    }
}
