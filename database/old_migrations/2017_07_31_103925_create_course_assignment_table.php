<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseAssignmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_assignment', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_id');
            // $table->foreign('student_id')->references('id')->on('students');
            $table->integer('course_id');
            // $table->foreign('course_id')->references('id')->on('courses');
            $table->integer('semester_id');
            // $table->foreign('semester_id')->references('id')->on('academic_semesters');
            $table->enum('doing_as', ['carryover','elective']);
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
        Schema::dropIfExists('course_assignment');
    }
}
