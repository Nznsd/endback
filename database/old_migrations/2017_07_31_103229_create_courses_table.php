<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('programme_id');
            // $table->foreign('programme_id')->references('id')->on('programmes');
            $table->integer('specialization_id');
            // $table->foreign('specialization_id')->references('id')->on('specializations');
            $table->enum('level', ['100', '200', '300', '400']);
            $table->enum('semester', ['1', '2']);
            $table->string('code');
            $table->string('title');
            $table->integer('credit_unit');
            $table->enum('status', ['core', 'elective']);
            $table->string('desc');
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
        Schema::dropIfExists('courses');
    }
}
