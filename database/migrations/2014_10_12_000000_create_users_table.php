<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            // $table->uuid('user_id')->primary();
            $table->increments('user_id');
            $table->string('email')->length(50)->unique()->nullable();
            $table->tinyInteger('access_level')->default(0);
            $table->string('password')->length(60);
            $table->dateTime('email_verified')->nullable();
            $table->string('provider')->length(20)->nullable();
            $table->string('secret_key')->length(20);
            $table->timestamps();
            $table->softDeletes();
            $table->rememberToken();
            $table->tinyInteger('status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
