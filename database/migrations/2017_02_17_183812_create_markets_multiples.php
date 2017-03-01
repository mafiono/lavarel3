<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketsMultiples extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_markets_multiples', function (Blueprint $table) {
            $table->increments('id');
            $table->text('markets');
            $table->timestamps();
        });
        Schema::table('user_bets', function (Blueprint $table) {
            $table->integer('market_id')->unsigned()->nullable()->after('api_withdrawal_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sports_markets_multiples');
        Schema::table('user_bets', function (Blueprint $table) {
            $table->dropColumn('market_id');
        });
    }
}
