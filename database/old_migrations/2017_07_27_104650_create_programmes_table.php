<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgrammesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('programmes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('abbr');
            $table->string('name');
            $table->integer('min_duration');
            $table->integer('max_duration');
            $table->enum('grading_system', ['gpa', 'normal']);
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
        Schema::dropIfExists('programmes');
    }

}
