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
            if (!Schema::hasColumn('user_bets', 'type'))
                $table->string('type', 45);
            if (!Schema::hasColumn('user_bets', 'tax'))
                $table->float('tax');


            if (Schema::hasColumn('user_bets', 'initial_balance'))
                $table->dropColumn('initial_balance');
            if (Schema::hasColumn('user_bets', 'final_balance'))
                $table->dropColumn('final_balance');
            if (Schema::hasColumn('user_bets', 'initial_win_balance'))
                $table->dropColumn('initial_win_balance');
            if (Schema::hasColumn('user_bets', 'initial_bonus'))
                $table->dropColumn('initial_bonus');
            if (Schema::hasColumn('user_bets', 'final_bonus'))
                $table->dropColumn('final_bonus');
            if (Schema::hasColumn('user_bets', 'sys_bet'))
                $table->dropColumn('sys_bet');
            if (Schema::hasColumn('user_bets', 'result_tax'))
                $table->dropColumn('result_tax');

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
