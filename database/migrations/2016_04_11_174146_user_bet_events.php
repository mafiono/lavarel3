<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserBetEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('user_bet_events'))
            Schema::create('user_bet_events', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_bet_id')->unsigned();
                $table->decimal('odd', 15, 2);
                $table->string('status', 45);
                $table->string('event_name');
                $table->string('market_name');
                $table->string('game_name');
                $table->dateTime('game_date');
                $table->integer('api_event_id')->unsigned();
                $table->integer('api_market_id')->unsigned();
                $table->integer('api_game_id')->unsigned();
                $table->timestamps();
                $table->foreign('user_bet_id')->references('id')->on('user_bets');
            });

        if (!Schema::hasColumn('user_bet_events', 'odd'))
            Schema::drop('user_bet_events');

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
