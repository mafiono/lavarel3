<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserBalanceLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_balance_logs', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->date('date');

            $table->decimal('balance_available', 15, 2);
            $table->decimal('balance_captive', 15, 2);
            $table->decimal('balance_bonus', 15, 2);
            $table->decimal('balance_accounting', 15, 2);
            $table->decimal('balance_total', 15, 2);

            $table->timestamps();

            $table->unique(['user_id', 'date']);
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_balance_logs');
    }
}
