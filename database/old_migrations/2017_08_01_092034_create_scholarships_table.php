<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScholarshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scholarships', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fee_id');
            // $table->foreign('fee_id')->references('id')->on('fee_categories');
            $table->integer('semester_id');
            // $table->foreign('semester_id')->references('id')->on('academic_semesters');
            $table->string('title');
            $table->string('payerName');
            $table->string('payerEmail');
            $table->string('payerPhone');
            $table->string('amount');
            $table->integer('partyA');
            $table->integer('partyB');
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
        Schema::dropIfExists('scholarships');
    }
}
