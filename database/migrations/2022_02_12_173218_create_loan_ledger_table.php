<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanLedgerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_ledger', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->references('id')->on('loans');
            $table->decimal('paid_amount',14,4)->default('0');
            $table->decimal('remaining_amount',14,4);
            $table->integer('paid_installments')->default(0);
            $table->integer('remaining_installments');
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
        Schema::dropIfExists('loan_ledger');
    }
}
