<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBonus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bonus', function (Blueprint $table) {
            if (!Schema::hasColumn('bonus', 'head_id'))
                $table->integer('head_id')->unsigned();

            if (!Schema::hasColumn('bonus', 'current'))
                $table->boolean('current')->default(true);

            if (!Schema::hasColumn('bonus', 'rollover_coefficient'))
                $table->decimal('rollover_coefficient', 15, 2);
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
