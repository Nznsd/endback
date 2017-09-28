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
            $table->bigIncrements('id');
            $table->integer('fee_id');
            $table->integer('semester_id');
            $table->string('param');
            $table->string('val');
            $table->integer('installment')->nullable();
            $table->decimal('amount');
            $table->string('order_id');
            $table->json('remita_before')->nullable();
            $table->json('remita_after')->nullable();
            $table->string('status')->default('unpaid');
            $table->string('fee_table');
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