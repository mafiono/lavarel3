<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserBetTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_bet_transactions', function (Blueprint $table) {
            if (!Schema::hasColumns('user_bet_transactions', [
                'user_bet_status_id',
                'amount_bonus',
                'initial_balance',
                'final_balance',
                'initial_bonus',
                'final_bonus'
            ])) {
                $table->integer('user_bet_status_id')->unsigned();
                $table->decimal('amount_bonus', 15, 2);
                $table->decimal('initial_balance', 15, 2);
                $table->decimal('final_balance', 15, 2);
                $table->decimal('initial_bonus', 15, 2);
                $table->decimal('final_bonus', 15, 2);
            }

            if (Schema::hasColumns('user_bet_transactions', [
                'amount',
                'type',
                'description',
            ])) {
                $table->renameColumn('amount', 'amount_balance');
                $table->dropColumn('type');
                $table->dropColumn('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
