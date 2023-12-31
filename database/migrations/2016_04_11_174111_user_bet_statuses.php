<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserBetStatuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_bet_statuses', function (Blueprint $table) {
            if (Schema::hasColumn('user_bet_statuses', 'user_session_id'))
                $table->dropColumn('user_session_id');
            if (Schema::hasColumn('user_bet_statuses', 'current'))
                $table->boolean('current')->default(1)->change();
            if (Schema::hasColumn('user_bet_statuses', 'status_id'))
                $table->renameColumn('status_id', 'status');
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
