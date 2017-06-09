<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreIndexesToBets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_bets', function (Blueprint $table) {
            $table->index('result');
            $table->index('status');
            $table->index('user_bonus_id');
            $table->index('created_at');
        });

        Schema::table('user_bet_events', function (Blueprint $table) {
            $table->index('status');
            $table->index('game_date');
            $table->index('api_game_id');
            $table->index('api_market_id');
            $table->index('api_event_id');
            $table->index('created_at');
        });

        Schema::table('user_bet_statuses', function (Blueprint $table) {
            $table->index('status');
            $table->index('current');
            $table->index('created_at');
        });

        Schema::table('user_bet_transactions', function (Blueprint $table) {
            $table->index('user_bet_id');
            $table->index('user_bet_status_id');
            $table->index('operation');
            $table->index('created_at');
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
            $table->dropIndex(['result']);
            $table->dropIndex(['status']);
            $table->dropIndex(['user_bonus_id']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('user_bet_events', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['game_date']);
            $table->dropIndex(['api_game_id']);
            $table->dropIndex(['api_market_id']);
            $table->dropIndex(['api_event_id']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('user_bet_statuses', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['current']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('user_bet_transactions', function (Blueprint $table) {
            $table->dropIndex(['user_bet_id']);
            $table->dropIndex(['user_bet_status_id']);
            $table->dropIndex(['operation']);
            $table->dropIndex(['created_at']);
        });
    }
}
