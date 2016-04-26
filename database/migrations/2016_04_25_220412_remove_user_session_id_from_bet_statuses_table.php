<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveUserSessionIdFromBetStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_bet_statuses', function (Blueprint $table) {
            $table->dropColumn('user_session_id');
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
            $table->integer('user_session_id')->unsigned();
        });

    }
}
