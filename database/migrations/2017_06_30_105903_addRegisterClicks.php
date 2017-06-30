<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRegisterClicks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->integer('regists');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('ad')->nullable();
        });

        Schema::create('adclicks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_id')->unsigned();
            $table->string('ip', 50);
            $table->foreign('ad_id')->references('id')->on('ads');
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
        Schema::table('ads', function (Blueprint $table) {
            $table->dropColumn('regists');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('ad');
        });
    }
}
