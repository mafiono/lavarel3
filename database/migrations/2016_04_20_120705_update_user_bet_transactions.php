<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserBetTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_bet_transactions', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('description');
            $table->decimal('amount_bonus', 15, 2);
            $table->decimal('initial_balance', 15, 2);
            $table->decimal('final_balance', 15, 2);
            $table->decimal('initial_bonus', 15, 2);
            $table->decimal('final_bonus', 15, 2);
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
            $table->string('type', 10);
            $table->string('description');
            $table->dropColumn('amount_bonus');
            $table->dropColumn('initial_balance');
            $table->dropColumn('final_balance');
            $table->dropColumn('initial_bonus');
            $table->dropColumn('final_bonus');
        });
    }
}
