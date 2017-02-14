<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAuthorizedToCompetitions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('betgenius.competitions', function (Blueprint $table) {
            $table->boolean('authorized');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('betgenius.competitions', function (Blueprint $table) {
            $table->removeColumn('authorized');
        });
    }
}
