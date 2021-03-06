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
            $table->bigInteger('user_id')->unique()->nullable();
            $table->string('reg_no')->unique()->nullable();
            $table->string('exam_no')->nullable();
            $table->string('surname')->nullable();
            $table->string('firstname')->nullable();
            $table->string('othername')->nullable();
            $table->string('recovery_email')->nullable();            
            $table->string('phone')->nullable();
            $table->date('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('address')->nullable();
            $table->integer('sor')->nullable();
            $table->integer('sor_lga')->nullable();
            $table->integer('soo')->nullable();
            $table->integer('soo_lga')->nullable();
            $table->integer('programme_id')->nullable();
            $table->integer('specialization_id')->nullable();
            $table->integer('study_center_id')->nullable();
            $table->integer('entry_level')->nullable();
            $table->integer('entry_year')->nullable();
            $table->integer('exit_year')->nullable();
            $table->integer('entry_semester')->nullable();
            $table->string('entry_type')->default('fresh');
            $table->bigInteger('applicant_id')->nullable();
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
