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
            $table->string('manager')->nullable();
            $table->string('manager_phone')->nullable();
            $table->string('desk')->nullable();
            $table->string('desk_phone')->nullable();
            $table->string('status')->default('active');
            $table->json('mergee')->nullable();
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
