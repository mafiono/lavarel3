<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EmailInvites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('email_invites'))
            Schema::create('email_invites', function (Blueprint $table) {
                $table->integer('user_id')->unsigned();
                $table->string('email', 45);
                $table->timestamps();
                $table->primary(['user_id', 'email']);
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
    }
}
