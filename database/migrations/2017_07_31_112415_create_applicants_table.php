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
            $table->integer('user_id');
            $table->string('app_no')->nullable();
            $table->string('surname');
            $table->string('firstname');
            $table->string('othername');
            $table->string('email');
            $table->string('phone');
            $table->date('dob');
            $table->string('gender');
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
            $table->integer('application_state');
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
