<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Addfieldstostaff extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff', function(Blueprint $table) {


            $table->integer('oral_french');
            $table->integer('oral_dutch');
            $table->integer('written_french');
            $table->integer('written_dutch');
            $table->string('upload_jobdescription');
            $table->string('upload_amorim');
            $table->integer('email_id');
            
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
