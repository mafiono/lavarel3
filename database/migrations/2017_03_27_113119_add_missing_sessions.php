<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMissingSessions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_bonus', function (Blueprint $table) {
            $table->integer('user_session_id')->unsigned()->nullable()->after('user_id');

            $table->foreign('user_session_id')->references('id')->on('user_sessions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_bonus', function (Blueprint $table) {
            $table->dropColumn('user_session_id');
        });
    }
}
