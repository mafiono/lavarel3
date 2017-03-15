<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserMails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_mails', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('user_session_id')->unsigned();
            $table->string('type', 250);
            $table->string('title', 250);
            $table->text('message');
            $table->string('to', 250);
            $table->boolean('resend')->default(0);
            $table->text('error')->nullable();
            $table->boolean('sent')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('user_session_id')->references('id')->on('user_sessions');
            $table->index('resend');
            $table->index('sent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_mails');
    }
}
