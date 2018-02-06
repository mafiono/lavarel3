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
        Schema::create('cp_fixtures', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_guid')->unique();
            $table->string('status', 50);
            $table->string('odd', 50);
            $table->integer('fixture_id');
            $table->integer('id_pai');
            $table->integer('type');
            $table->integer('cp_fixture_type_id')->nullable();
            $table->string('image');
            $table->timestamps();
        });
        Schema::create('cp_fixture_values', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('amount');
            $table->integer('cp_fixture_id');
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
        Schema::drop('cp_fixtures');
        Schema::drop('cp_fixture_values');

    }
}
