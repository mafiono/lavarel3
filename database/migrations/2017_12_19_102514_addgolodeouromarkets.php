<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Addgolodeouromarkets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('golodeouro_markets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->integer('golodeouro_id');
            $table->timestamps();
        });
        Schema::create('golodeouro_selections', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('odd');
            $table->integer('golodeouro_market_id');
            $table->string('result_status');
            $table->string('name');
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
        Schema::drop('golodeouro_markets');
        Schema::drop('golodeouro_selections');
    }
}
