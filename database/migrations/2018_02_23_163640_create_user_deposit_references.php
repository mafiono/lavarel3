<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserDepositReferences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_deposit_references', function (Blueprint $table) {
            $table->collation = 'utf8_general_ci';

            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('origin', 45);
            $table->integer('version');
            $table->integer('user_session_id')->unsigned();
            $table->string('entity', 5);
            $table->string('ref', 9);
            $table->boolean('active');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('origin')->references('id')->on('transactions');

            $table->unique(['user_id', 'origin', 'version']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_deposit_references');
    }
}
