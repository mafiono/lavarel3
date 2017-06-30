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

        Schema::create('adclick', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ip', 50);
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
