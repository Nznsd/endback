<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpecializationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('specializations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dept_code');
            $table->string('abbr');
            $table->string('name');
            $table->integer('programme_id');
            $table->string('status');
            $table->boolean('practical')->nullable();
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
        Schema::dropIfExists('specializations');
    }

   
}
