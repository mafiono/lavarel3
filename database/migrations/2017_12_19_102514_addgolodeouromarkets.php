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
        Schema::create('cp_markets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->integer('cp_fixture_id')->unsigned();
            $table->timestamps();

            $table->foreign('cp_fixture_id')->references('id')->on('cp_fixtures');
        });
        Schema::create('cp_selections', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('odd', 15, 2);
            $table->integer('cp_market_id')->unsigned();
            $table->string('result_status', 20);
            $table->string('name', 100);
            $table->timestamps();

            $table->foreign('cp_market_id')->references('id')->on('cp_markets');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cp_markets');
        Schema::drop('cp_selections');
    }
}
