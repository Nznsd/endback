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
            $table->integer('installment');
            $table->decimal('amount', 10, 2);
            $table->string('orderId');
            $table->json('remitaBefore')->nullable();
            $table->json('remitaAfter')->nullable();
            $table->string('status')->default('unpaid');
            $table->string('remark')->default('N/A');            
            $table->string('fee_table');
            $table->integer('fee_table_id');
            $table->json('archive')->nullable();            
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
