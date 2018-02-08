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
            $table->decimal('odd', 15, 2);
            $table->integer('bets_number');
            $table->decimal('bet_amount', 15, 2);
            $table->decimal('average', 15, 2);
            $table->decimal('ggr', 15, 2);
            $table->decimal('ggr_tax', 15, 2);
            $table->decimal('paid', 15, 2);
            $table->decimal('proft', 15, 2);
            $table->decimal('proft_historical', 15, 2);
            $table->integer('winners');
            $table->integer('cp_fixture_id')->unsigned();
            $table->integer('cp_fixture_type')->unsigned();
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
