<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserBetStatuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_bet_statuses', function (Blueprint $table) {
            $table->decimal('initial_balance', 15, 2);
            $table->decimal('final_balance', 15, 2);
            $table->decimal('initial_bonus', 15, 2);
            $table->decimal('final_bonus', 15, 2);
            $table->integer('user_bet_transaction_id')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_bet_statuses', function (Blueprint $table) {
            $table->dropColumn('initial_balance');
            $table->dropColumn('final_balance');
            $table->dropColumn('initial_bonus');
            $table->dropColumn('final_bonus');
            $table->dropColumn('user_bet_transaction_id');
        });
    }
}
