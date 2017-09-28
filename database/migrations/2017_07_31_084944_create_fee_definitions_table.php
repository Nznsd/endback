<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeeDefinitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fee_definitions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fee_id');
            $table->integer('programme_id');
            $table->integer('specialization_id');
            $table->integer('level');
            $table->integer('semester');
            $table->decimal('amount');
            $table->string('category')->default('fresh');
            $table->json('installment')->nullable();
            $table->json('beneficiaries')->nullable();
            $table->json('collection')->nullable();
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
        Schema::dropIfExists('fee_definitions');
    }
}
