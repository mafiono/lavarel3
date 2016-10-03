<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetUsersRatingDefaults extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('rating_risk', 20)->default('Risk0')->change();
            $table->string('rating_group', 20)->default('Walk')->change();
            $table->string('rating_type', 20)->default('Mouse')->change();
            $table->string('rating_category', 20)->default('Both')->change();
            $table->string('rating_class', 20)->default('Test')->change();
            $table->string('rating_status', 20)->default('pending')->change();
        });
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
