<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Definicoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('definicoes'))
            Schema::create('definicoes', function (Blueprint $table) {
                $table->increments('id');
                $table->string('ipV4', 50)->nullable();
                $table->string('ipV6', 50)->nullable();
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
        //
    }
}
