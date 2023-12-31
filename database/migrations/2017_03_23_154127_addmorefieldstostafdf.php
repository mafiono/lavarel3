<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Addmorefieldstostafdf extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff', function(Blueprint $table) {


            $table->string('upload_isms');
            $table->boolean('office_keys');
            $table->string('office');
            $table->string('upload_iban');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staff', function(Blueprint $table) {
            $table->dropColumn('upload_isms');
            $table->dropColumn('office_keys');
            $table->dropColumn('office');
            $table->dropColumn('upload_iban');

        });
    }
}
