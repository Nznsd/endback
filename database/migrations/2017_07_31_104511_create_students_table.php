<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('reg_no');
            $table->string('exam_no');
            $table->string('surname');
            $table->string('firstname');
            $table->string('othername');
            $table->string('email');
            $table->string('phone');
            $table->date('dob');
            $table->string('gender');
            $table->string('marital_status');
            $table->string('address');
            $table->integer('sor');
            $table->integer('sor_lga');
            $table->integer('soo');
            $table->integer('soo_lga');
            $table->integer('programme_id');
            $table->integer('specialization_id');
            $table->integer('study_center_id');
            $table->integer('entry_level');
            $table->integer('entry_year');
            $table->integer('exit_year');
            $table->integer('entry_semester');
            $table->string('entry_type')->default('fresh');
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
        Schema::dropIfExists('students');
    }
}
