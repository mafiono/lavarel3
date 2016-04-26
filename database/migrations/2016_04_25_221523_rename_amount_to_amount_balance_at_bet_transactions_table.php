<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameAmountToAmountBalanceAtBetTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_bet_transactions', function (Blueprint $table) {
            $table->dropColumn('amount');
            $table->decimal('amount_balance', 15, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_bet_transactions', function (Blueprint $table) {
            $table->dropColumn('amount_balance');
            $table->decimal('amount', 15, 2);
        });

    }
}
