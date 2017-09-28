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
            $table->string('staff_id');
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
            $table->integer('sor');
            $table->integer('sor_lga');
            $table->integer('soo');
            $table->integer('soo_lga');
            $table->integer('sub_division_id');
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
