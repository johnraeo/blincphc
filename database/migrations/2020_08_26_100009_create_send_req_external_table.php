<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSendReqExternalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('send_req_external', function (Blueprint $table) {
            $table->integer('send_req_external_id')->primary();
            $table->string('account_name')->length(300);
            $table->integer('send_req_id')->unsigned();
            $table->foreign('send_req_id')->references('send_req_id')->on('sned_req');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('send_req_external');
    }
}
