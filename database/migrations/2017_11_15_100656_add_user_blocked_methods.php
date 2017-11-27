<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserBlockedMethods extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_blocked_methods', function (Blueprint $table) {
            $table->collation = 'utf8_general_ci';

            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('method_id', 45);
            $table->string('reason', 500)->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('method_id')->references('id')->on('transactions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_blocked_methods');
    }
}
