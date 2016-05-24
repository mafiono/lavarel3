<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('user_bets_bc');
        Schema::dropIfExists('user_bets_nyx');
        Schema::dropIfExists('user_bets_bc_events');
        Schema::dropIfExists('user_results_nyx');
        Schema::dropIfExists('user_rollbacks_nyx');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
