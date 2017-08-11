<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BonusDepositMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonus_deposit_methods', function (Blueprint $table) {
           $table->integer('bonus_id')->unsigned();
           $table->string('deposit_method_id', 45);
           $table->timestamps();

           $table->foreign('bonus_id')->references('id')->on('bonus');

           $table->index('deposit_method_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bonus_deposit_methods');
    }
}
