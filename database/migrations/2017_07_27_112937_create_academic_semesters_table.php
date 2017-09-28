<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcademicSemestersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::enableForeignKeyConstraints();

        Schema::create('academic_semesters', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('academic_session_id');
            $table->integer('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status')->default('yellow'); //green, yellow, red 
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
        Schema::dropIfExists('academic_semesters');
    }

}
