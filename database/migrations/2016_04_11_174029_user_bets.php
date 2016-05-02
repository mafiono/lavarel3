<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserBets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_bets', function (Blueprint $table) {

            if (!Schema::hasColumn('user_bets', 'user_betslip_id'))
                $table->integer('user_betslip_id')->unsigned();
            if (!Schema::hasColumn('user_bets', 'user_bonus_id'))
                $table->integer('user_bonus_id')->unsigned();
            if (!Schema::hasColumn('user_bets', 'amount_taxed'))
                $table->decimal('amount_taxed', 15, 2);
            if (!Schema::hasColumn('user_bets', 'odd'))
                $table->decimal('odd', 15, 2);

            if (Schema::hasColumns('user_bets', [
                'initial_balance',
                'final_balance',
                'initial_win_balance',
                'initial_bonus',
                'final_bonus'])) {
                $table->dropColumn('initial_balance');
                $table->dropColumn('final_balance');
                $table->dropColumn('initial_win_balance');
                $table->dropColumn('initial_bonus');
                $table->dropColumn('final_bonus');
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
