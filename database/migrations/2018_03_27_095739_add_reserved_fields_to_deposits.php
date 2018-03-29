<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReservedFieldsToDeposits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_transactions', function (Blueprint $table){
            $table->decimal('debit_reserve', 15, 2)->default(0)->after('final_bonus');
            $table->decimal('credit_reserve', 15, 2)->default(0)->after('debit_reserve');
        });
        Schema::table('user_balances', function (Blueprint $table){
            $table->decimal('balance_reserved', 15, 2)->default(0)->after('b_ac_check');
            $table->decimal('b_re_check', 15, 2)->default(0)->after('balance_reserved');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_transactions', function (Blueprint $table){
            $table->dropColumn('debit_reserve');
            $table->dropColumn('credit_reserve');
        });
        Schema::table('user_balances', function (Blueprint $table){
            $table->dropColumn('balance_reserved');
            $table->dropColumn('b_re_check');
        });
    }
}
