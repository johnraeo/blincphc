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
            $table->bigIncrements('transact_id');
            
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('user_id')->on('users');

            $table->integer('participant')->nullable()->unsigned();
            $table->foreign('participant')->references('user_id')->on('users');
            
            $table->double('running_balance')->length(20);
            $table->double('amount')->length(20);
            $table->tinyInteger('status');
            $table->dateTime('transact_date');
            $table->dateTime('updated_at')->nullable();
            $table->string('transact_type')->length(2);
            $table->tinyInteger('movement');
            $table->bigInteger('transact_type_id');
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
