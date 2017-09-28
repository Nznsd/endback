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
            $table->increments('id');
            $table->integer('fee_id');
            // $table->foreign('fee_id')->references('id')->on('fee_categories');
            $table->integer('semester_id');
            // $table->foreign('semester_id')->references('id')->on('academic_semesters');
            // $table->enum('param', ['programme', 'specialization', 'level', 'std', 'app']);
            $table->string('param');
            $table->string('val');
            $table->enum('level', ['0','1','2','3','4'])->default('0');
            $table->enum('nature', ['add', 'overwrite'])->default('add');
            $table->double('amount');
            $table->json('installment');
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
        Schema::dropIfExists('fee_assignments');
    }
}
