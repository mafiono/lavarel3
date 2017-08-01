<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAptrToUserBetEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_bet_events', function (Blueprint $table) {
            $table->string('api_aptr_id', 20)->nullable()->after('api_game_id');

            $table->index('api_aptr_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_bet_events', function (Blueprint $table) {
            $table->dropColumn('api_aptr_id');
        });
    }
}
