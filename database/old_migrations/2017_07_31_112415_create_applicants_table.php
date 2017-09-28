<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicants', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            // $table->foreign('user_id')->references('id')->on('users');
            $table->string('surname');
            $table->string('firstname');
            $table->string('othername');
            $table->string('email');
            $table->string('phone');
            $table->date('dob');
            $table->string('gender');
            $table->string('marital_status')->nullable();
            $table->string('address')->nullable();
            $table->string('sor')->nullable();
            $table->string('sor_lga')->nullable();
            $table->string('soo')->nullable();
            $table->string('soo_lga')->nullable();
            $table->string('lga')->nullable();
            $table->integer('semester_id')->nullable();
            // $table->foreign('semester_id')->references('id')->on('academic_semesters');
            $table->integer('programme_id')->nullable();
            // $table->foreign('programme_id')->references('id')->on('programmes');
            $table->string('first_choice')->nullable();
            $table->string('second_choice')->nullable();
            $table->enum('entry_type',['fresh','DE','transfer'])->nullable();
            $table->integer('entry_level')->nullable();
            $table->integer('entry_semester')->nullable();
            $table->integer('study_center_id')->nullable();
            // $table->foreign('study_center_id')->references('id')->on('study_centers');
            $table->integer('application_step')->nullable();
            $table->enum('application_status',['false','true'])->nullable();
            
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
        Schema::dropIfExists('applicants');
    }
}
