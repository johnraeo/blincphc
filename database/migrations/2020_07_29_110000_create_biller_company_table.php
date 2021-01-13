<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillerCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('biller_company', function (Blueprint $table) {
            $table->bigIncrements('biller_id');
            $table->string('company_name')->length(100);
            $table->string('image')->nullable();
            $table->string('reference')->length(500);
            $table->tinyInteger('category');
            $table->tinyInteger('status');
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
        Schema::dropIfExists('biller_company');
    }
}
