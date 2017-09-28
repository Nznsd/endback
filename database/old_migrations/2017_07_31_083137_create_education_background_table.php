<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEducationBackgroundTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('education_background', function (Blueprint $table) {
            $table->increments('id');
            // $table->enum('key', ['std', 'app']);
            $table->string('param');
            $table->string('val');
            $table->string('certificate_name');
            $table->json('school');
            $table->json('grades');
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
        Schema::dropIfExists('education_background');
    }
}
