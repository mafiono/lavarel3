<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAffiliates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('affiliates');

        Schema::create('affiliates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('prefix', 36)->unique();
            $table->string('name');
            $table->float('iejosb');
            $table->float('bonussb');
            $table->float('depositsb');
            $table->float('iejocasino');
            $table->float('bonuscasino');
            $table->float('depositcasino');
            $table->boolean('expire')->default(1);
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
        Schema::drop('affiliates');
    }
}
