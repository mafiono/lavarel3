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
        // Needs to be updated on future
        if (!Schema::hasTable('user_bet_events'))
            Schema::create('user_bet_events', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_bet_id');
                $table->decimal('price', 15, 2);
                $table->string('event_name');
                $table->string('match_name');
                $table->string('bet_name');
                $table->dateTime('match_date');
                $table->string('status_id', 45);
                $table->integer('api_odd_id');
                $table->integer('api_match_id');
                $table->integer('api_tournament_id');
                $table->integer('api_contry_id');
                $table->timestamp('created_at');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {;
    }
}
