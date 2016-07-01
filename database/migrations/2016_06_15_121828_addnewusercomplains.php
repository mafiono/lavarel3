<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Addnewusercomplains extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_complains', function (Blueprint $table) {
            $table->increments('id');
            $table->text('complaint', '200');
            $table->integer('user_id')->unsigned();
            $table->integer('staff_id')->unsigned();
            $table->integer('staff_session_id')->unsigned();
            $table->integer('user_session_id')->unsigned();
            $table->date('data');
            $table->date('solution_time');
            $table->text('solution', '200');
            $table->string('result', '50');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('user_session_id')->references('id')->on('user_sessions');
            //$table->foreign('staff_id')->references('id')->on('staffs');
//            $table->foreign('staff_session_id')->references('id')->on('staff_sessions');

            $table->index('id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_complains');
    }
}
