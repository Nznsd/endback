<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeeStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fee_structures', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fee_id');
            // $table->foreign('fee_id')->references('id')->on('fee_categories');
            $table->integer('programme_id');
            // $table->foreign('programme_id')->references('id')->on('programmes');
            $table->integer('specialization_id');
            // $table->foreign('specialization_id')->references('id')->on('specializations');
            $table->enum('level', ['0', '1', '2', '3','4'])->default('0');
            $table->enum('semester', ['0','1', '2'])->default('0');
            $table->double('amount');
            $table->enum('category', ['Fresh', 'DE', 'Transfer'])->default('Fresh');
            $table->json('installment');
            $table->double('firstInstall');
            $table->double('secondInstall');
            $table->double('partyA');
            $table->double('partyB');
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
        Schema::dropIfExists('fee_structures');
    }
}
