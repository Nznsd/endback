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
            $table->bigIncrements('id');
            $table->integer('applicant_id');
            $table->integer('programme_id');
            $table->integer('specialization_id');
            $table->integer('entry_year');
            $table->integer('entry_level');
            $table->integer('entry_semester');
            $table->string('entry_type');
            $table->string('duration');
            $table->string('param');
            $table->string('val')->default('pending');
            $table->integer('action_by');
            $table->integer('student_id');           
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
