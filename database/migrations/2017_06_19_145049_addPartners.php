<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPartners extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

        public function up()
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->boolean('active');
            $table->timestamps();
        });
        Schema::create('ads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('partner_id')->unsigned();
            $table->string('link', 250)->nullable();
            $table->integer('clicks')->nullable();
            $table->string('image', 250)->nullable();
            $table->timestamps();
            $table->foreign('partner_id')->references('id')->on('partners');
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
