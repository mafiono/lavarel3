<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBonusCasinoGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonus_casino_games', function (Blueprint $table) {
            $table->integer('bonus_id')->unsigned();
            $table->string('game_id', 32);

            $table->foreign('bonus_id')->references('id')->on('users');
            $table->index('game_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bonus_casino_games');
    }
}