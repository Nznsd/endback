<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unique()->nullable();
            $table->string('staff_id')->nullable();
            $table->string('title')->nullable();
            $table->string('surname')->nullable();
            $table->string('firstname')->nullable();
            $table->string('othername')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_no')->nullable();
            $table->date('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('address')->nullable();
            $table->string('position')->nullable();
            $table->integer('sor')->nullable();
            $table->integer('sor_lga')->nullable();
            $table->integer('soo')->nullable();
            $table->integer('soo_lga')->nullable();
            $table->integer('sub_division_id')->nullable();
            $table->json('data')->nullable();
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
        Schema::dropIfExists('staff');
    }
}
