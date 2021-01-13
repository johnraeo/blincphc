<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSecuritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('securities', function (Blueprint $table) {
            $table->integer('security_id')->primary();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->tinyInteger('is_private')->default(0);
            $table->tinyInteger('otp_enabled')->default(0);
            $table->string('tfa_secret')->length(100)->nullable();
            $table->tinyInteger('tfa_enabled')->default(0);
            $table->tinyInteger('mpin_enabled')->default(0);
            $table->integer('max_trials')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('securities');
    }
}
