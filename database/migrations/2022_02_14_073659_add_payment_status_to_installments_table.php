<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentStatusToInstallmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('installments', function (Blueprint $table) {
            $table->tinyInteger('payment_status')->default(0)->after('installment_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('installments', function (Blueprint $table) {
            $table->dropColumn('payment_status');
        });
    }
}
