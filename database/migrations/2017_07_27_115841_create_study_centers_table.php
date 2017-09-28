<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudyCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('study_centers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('state_id');
            $table->string('code');
            $table->string('name');
            $table->integer('programme_id');
            $table->string('manager');
            $table->string('manager_phone');
            $table->string('desk');
            $table->string('desk_phone');
            $table->string('status');
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
        Schema::dropIfExists('study_centers');
    }
}
