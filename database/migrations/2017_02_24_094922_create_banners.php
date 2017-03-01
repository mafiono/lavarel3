<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBanners extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 50);
            $table->string('description', 150)->nullable();
            $table->string('message', 500)->nullable();
            $table->string('link', 250)->nullable();
            $table->string('image', 250)->nullable();
            $table->string('size', 200)->nullable();
            $table->timestamp('start');
            $table->timestamp('end')->nullable();
            $table->boolean('active');
            $table->timestamps();
        });
        Schema::create('banners_frames', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('banner_id')->unsigned();
            $table->string('description', 150)->nullable();
            $table->string('message', 500)->nullable();
            $table->string('link', 250)->nullable();
            $table->string('image', 250)->nullable();
            $table->string('location', 200)->nullable();
            $table->integer('order_id');
            $table->timestamps();

            $table->foreign('banner_id')->references('id')->on('banners');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('banners_frames');
        Schema::drop('banners');
    }
}
