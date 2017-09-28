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
            $table->increments('id');
            $table->integer('user_id');
            // $table->foreign('user_id')->references('id')->on('users');
            $table->integer('staff_id');
            $table->string('title');
            $table->string('surname');
            $table->string('firstname');
            $table->string('othername');
            $table->string('email');
            $table->string('phone_no');
            $table->date('dob');
            $table->string('gender');
            $table->string('marital_status');
            $table->string('address');
            $table->string('position');
            $table->string('soo');
            $table->string('lga');
            $table->integer('sub_dept_id');
            // $table->foreign('sub_dept_id')->references('id')->on('sub_departments');
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
