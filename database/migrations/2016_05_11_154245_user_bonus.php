<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserBonus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('user_bonus'))
            Schema::table('user_bonus', function (Blueprint $table){
                if (!Schema::hasColumn('user_bonus', 'bonus_value'))
                    $table->decimal('bonus_value', 15, 2);
                if (!Schema::hasColumn('user_bonus', 'bonus_wagered'))
                    $table->decimal('bonus_wagered', 15, 2);
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
