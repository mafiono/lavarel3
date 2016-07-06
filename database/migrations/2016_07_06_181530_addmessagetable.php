<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Addmessagetable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('staff_id')->unsigned();
            $table->text('text', '550');
            $table->integer('viewed');
            $table->text('operator', '50');
            $table->integer('value');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('staff_id')->references('id')->on('staff');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('messages');
    }
}
