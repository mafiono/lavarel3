<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Addaccesses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff', function(Blueprint $table) {


            $table->boolean('baco');
            $table->boolean('vulcano');
            $table->boolean('cibele');
            $table->boolean('minerva');
            $table->boolean('mercurio');
            $table->boolean('fortuna');
            $table->boolean('concordia');
            $table->string('gitlabrole');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
