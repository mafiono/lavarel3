<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMailsConfigurations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_configurations', function(Blueprint $table) {
            $table->increments('id');

            $table->string('type', 100);
            $table->enum('origin', ['to', 'cc', 'bcc']);
            $table->string('name', 100);
            $table->string('email', 200);

            $table->integer('staff_id')->unsigned()->nullable();
            $table->integer('staff_session_id')->unsigned();

            $table->timestamps();

            $table->foreign('staff_id')->references('id')->on('staff');
            $table->foreign('staff_session_id')->references('id')->on('staff_sessions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('mail_configurations');
    }
}
