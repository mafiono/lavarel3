<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Addgolodeouro extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('golodeouro', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status', 50);
            $table->string('odd', 50);
            $table->integer('fixture_id');
            $table->timestamps();
        });
        Schema::create('golodeouro_values', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('amount');
            $table->integer('golodeouro_id');
            $table->timestamps();
        });

        Schema::table('user_bets', function (Blueprint $table) {
            $table->integer('golodeouro_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_bets', function (Blueprint $table) {

            $table->dropColumn('golodeouro_id');
        });
        Schema::drop('golodeouro');
        Schema::drop('golodeouro_values');

    }
}
