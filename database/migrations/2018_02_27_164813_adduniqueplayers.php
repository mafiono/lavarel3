<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUniquePlayers extends Migration
{
    public function up()
    {
        Schema::table('cp_fixture_summarizeds', function (Blueprint $table) {
            $table->integer('unique_players');

        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cp_fixture_summarizeds', function (Blueprint $table) {
            $table->dropColumn('unique_players');
        });
    }
}

