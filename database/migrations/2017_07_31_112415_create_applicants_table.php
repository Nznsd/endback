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
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unique()->nullable();
            $table->string('app_no')->unique()->nullable();
            $table->string('surname')->nullable();
            $table->string('firstname')->nullable();
            $table->string('othername')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->date('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('address')->nullable();
            $table->integer('sor')->nullable();
            $table->integer('sor_lga')->nullable();
            $table->integer('soo')->nullable();
            $table->integer('soo_lga')->nullable();
            $table->integer('semester_id')->nullable();
            $table->integer('programme_id')->nullable();
            $table->integer('first_choice')->nullable();
            $table->integer('second_choice')->nullable();
            $table->integer('entry_level')->nullable();
            $table->string('entry_type')->default('fresh');
            $table->integer('entry_semester')->nullable();
            $table->integer('study_center_id')->nullable();
            $table->integer('application_state')->nullable();
            $table->boolean('application_status')->nullable();
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
