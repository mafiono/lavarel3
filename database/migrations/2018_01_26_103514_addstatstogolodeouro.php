<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Addstatstogolodeouro extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cp_fixture_summarizeds', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name', 60);
        $table->dateTime('start_time');
        $table->double('odd');
        $table->integer('bets_number');
        $table->double('bet_amount');
        $table->double('average');
        $table->double('ggr');
        $table->double('ggr_tax');
        $table->double('paid');
        $table->double('proft');
        $table->double('proft_historical');
        $table->integer('winners');
        $table->integer('cp_fixture_id');
        $table->integer('cp_fixture_type');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cp_fixture_summarizeds');
    }
}
