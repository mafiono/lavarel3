<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserBets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_bets', function (Blueprint $table) {
            $table->integer('user_betslip_id')->unsigned();
            $table->integer('user_bonus_id')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_bets', function (Blueprint $table) {
            $table->dropColumn('user_betslip_id');
            $table->dropColumn('user_bonus_id');
        });
    }
}
