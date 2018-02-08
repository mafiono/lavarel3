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
            $table->string('id_guid', 36)->unique();
            $table->string('status', 20);
            $table->decimal('odd', 15, 2);
            $table->integer('fixture_id')->unsigned();
            $table->integer('id_pai')->unsigned()->nullable();
            $table->integer('type');
            $table->integer('cp_fixture_type_id')->unsigned()->nullable();
            $table->string('image');
            $table->timestamps();
        });
        Schema::create('cp_fixture_values', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('amount');
            $table->integer('cp_fixture_id')->unsigned();
            $table->timestamps();

            $table->foreign('cp_fixture_id')->references('id')->on('cp_fixtures');
        });
        Schema::table('user_bets', function (Blueprint $table) {
            $table->integer('cp_fixtures_id')->unsigned()->nullable();
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
            $table->dropColumn('cp_fixtures_id');
        });
        Schema::drop('cp_fixtures');
        Schema::drop('cp_fixture_values');

    }
}
