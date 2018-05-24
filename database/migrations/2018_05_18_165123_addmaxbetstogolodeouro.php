<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Addmaxbetstogolodeouro extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_fixtures', function (Blueprint $table){
            $table->integer('max_bets')->default(5);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cp_fixtures', function (Blueprint $table){
            $table->dropColumn('max_bets');
        });
    }
}
