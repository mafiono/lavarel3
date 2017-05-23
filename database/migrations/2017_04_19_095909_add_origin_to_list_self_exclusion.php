<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOriginToListSelfExclusion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('list_self_exclusions', function (Blueprint $table) {
            $table->string('origin', 20)->after('confirmed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('list_self_exclusions', function (Blueprint $table) {
            $table->dropColumn('origin');
        });
    }
}
