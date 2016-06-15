<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Errors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('errors', function (Blueprint $table) {
            $table->increments('id');
            $table->text('error');
            $table->enum('status', ['pending', 'fixing', 'fixed'])->default('pending');
            $table->string('solution');
            $table->string('msg');
            $table->string('type');
            $table->integer('staff_id')->unsigned();
            $table->timestamps();

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
        Schema::drop('errors');
    }
}
