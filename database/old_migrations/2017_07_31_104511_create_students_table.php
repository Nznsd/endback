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
            $table->increments('id');
            $table->integer('user_id');
            // $table->foreign('user_id')->references('id')->on('users');
            $table->integer('reg_no');
            $table->integer('exam_no');
            $table->string('surname');
            $table->string('firstname');
            $table->string('othername');
            $table->string('email');
            $table->string('phone');
            $table->date('dob');
            $table->string('gender');
            $table->string('marital_status');
            $table->string('address');
            $table->string('sor');
            $table->string('sor_lga');
            $table->string('soo');
            $table->string('soo_lga');
            $table->string('lga');
            $table->integer('programme_id');
            // $table->foreign('programme_id')->references('id')->on('programmes');
            $table->integer('specialization_id');
            // $table->foreign('specialization_id')->references('id')->on('specializations');
            $table->integer('study_center_id');
            // $table->foreign('study_center_id')->references('id')->on('study_centers');
            $table->integer('entry_year');
            $table->enum('entry_level', ['100','200','300','400']);
            $table->enum('entry_semester', ['first','second']);
            $table->enum('entry_type', ['fresh','DE','transfer']);
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
