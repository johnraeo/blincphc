<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->bigIncrements('device_id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->string('medium')->length(50);
            $table->string('ip')->length(25);
            $table->string('device_type')->length(50);
            $table->string('os')->length(50)->nullable();
            $table->string('platform')->length(50)->nullable();
            $table->string('brand_model')->length(50)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->string('hash')->length(100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devices');
    }
}
