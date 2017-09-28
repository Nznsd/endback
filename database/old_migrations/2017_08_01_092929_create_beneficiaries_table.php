<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBeneficiariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->increments('id');
            // $table->enum('key', ['std','app']);
            $table->string('key');
            $table->string('value');
            $table->integer('semester_id');
            // $table->foreign('semester_id')->references('id')->on('academic_semesters');
            $table->integer('scholarship_id');
            // $table->foreign('scholarship_id')->references('id')->on('scholarships');
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
        Schema::dropIfExists('beneficiaries');
    }
}
