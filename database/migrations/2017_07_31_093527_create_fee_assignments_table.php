<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeeAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fee_assignments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('fee_id');
            $table->integer('semester_id');
            $table->string('param');
            $table->string('val');
            $table->integer('level');
            $table->string('nature')->default('add');
            $table->decimal('amount');
            $table->json('installment')->nullable();
            $table->json('beneficiaries')->nullable();
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
        Schema::dropIfExists('fee_assignments');
    }
}
