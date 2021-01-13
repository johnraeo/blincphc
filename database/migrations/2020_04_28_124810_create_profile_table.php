<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile', function (Blueprint $table) {
            $table->integer('profile_id')->primary()->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->string('first_name')->length(50)->nullable();
            $table->string('last_name')->length(50)->nullable();
            $table->tinyInteger('gender')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('contact_number')->length(300)->nullable();
            $table->string('address')->length(300)->nullable();
            $table->binary('user_img')->nullable();
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
        Schema::dropIfExists('profile');
    }
}
