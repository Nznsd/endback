<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fee_id');
            // $table->foreign('fee_id')->references('id')->on('fee_categories');
            $table->integer('semester_id');
            // $table->foreign('semester_id')->references('id')->on('academic_semesters');
            // $table->enum('key', ['app', 'std']);
            $table->string('key');
            $table->string('value');
            $table->enum('installment',['0', '1', '2'])->default('0');
            $table->double('amount');
            $table->double('orderId');
            $table->json('remitaBefore');
            $table->json('remitaAfter');
            $table->enum('status', ['paid','not_paid']);
            $table->enum('fee_table',['fee_structures', 'fee_assignment', 'scholarship_fees']);
            $table->integer('fee_table_id');
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
        Schema::dropIfExists('transactions');
    }
}
