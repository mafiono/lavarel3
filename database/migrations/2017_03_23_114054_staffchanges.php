<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Staffchanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff', function(Blueprint $table) {


                $table->integer('cod_func');
                $table->string('upload_vaccines');
                $table->string('upload_recommendation');
                $table->string('upload_criminal');
                $table->string('upload_conduct');
                $table->text('otherlangs');
                $table->text('has_other');

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
