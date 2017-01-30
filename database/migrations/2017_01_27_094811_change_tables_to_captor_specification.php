<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTablesToCaptorSpecification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->string('name', 100)->change();
            $table->string('email', 100)->change();
            $table->string('address', 150)->change();
        });
        Schema::table('user_profiles_log', function (Blueprint $table) {
            $table->string('name', 100)->change();
            $table->string('email', 100)->change();
            $table->string('address', 150)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->string('name', 250)->change();
            $table->string('email', 250)->change();
            $table->text('address')->change();
        });
        Schema::table('user_profiles_log', function (Blueprint $table) {
            $table->string('name', 250)->change();
            $table->string('email', 45)->change();
            $table->text('address')->change();
        });
    }
}
