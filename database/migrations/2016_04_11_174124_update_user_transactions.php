<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_bet_transactions', function (Blueprint $table) {
            $table->integer('user_bet_status_id')->unsigned();
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
            $table->dropColumn('user_bet_status_id');
        });
    }
}
