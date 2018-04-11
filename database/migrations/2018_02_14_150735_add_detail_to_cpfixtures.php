<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDetailToCpfixtures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_fixtures', function (Blueprint $table) {
            $table->text('details')->nullable()->after('cp_fixture_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cp_fixtures', function (Blueprint $table) {
            $table->dropColumn('details');
        });
    }
}
