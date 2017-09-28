<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('applicant_id');
            // $table->foreign('applicant_id')->references('id')->on('applicants');
            $table->integer('programme_id');
            // $table->foreign('programme_id')->references('id')->on('programmes');
            $table->integer('specialization_id');
            // $table->foreign('specialization_id')->references('id')->on('specialzations');
            $table->integer('entry_year');
            $table->integer('entry_level');
            $table->string('entry_type');
            $table->string('duration');
            $table->enum('key', ['final','recommendation']);
            $table->enum('value', ['pending', 'admitted', 'probation', 'denied'])->default('pending');
            $table->integer('action_by');
            $table->integer('student_id');
            // $table->foreign('student_id')->references('id')->on('students');
           
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
        Schema::dropIfExists('admissions');
    }
}
