<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserBonus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_bonus', function (Blueprint $table){
            if (!Schema::hasColumn('user_bonus', 'id'))
                $table->increments('id');
            if (!Schema::hasColumn('user_bonus', 'bonus_head_id'))
                $table->integer('bonus_head_id')->unsigned();
            if (!Schema::hasColumn('user_bonus', 'promo_code'))
                $table->string('promo_code', 45);
            if (!Schema::hasColumn('user_bonus', 'balance_bonus'))
                $table->decimal('balance_bonus', 15, 2);
            if (!Schema::hasColumn('user_bonus', 'rollover_amount'))
                $table->decimal('rollover_amount', 15, 2)->unsigned();
            if (!Schema::hasColumn('user_bonus', 'deadline_date'))
                $table->dateTime('deadline_date');
            if (!Schema::hasColumn('user_bonus', 'active'))
                $table->boolean('active')->default(false);
            if (!Schema::hasColumn('user_bonus', 'deposited'))
                $table->boolean('deposited')->default(false);
            if (!Schema::hasColumn('user_bonus', 'bonus_value'))
                $table->decimal('bonus_value', 15, 2);
            if (!Schema::hasColumn('user_bonus', 'bonus_wagered'))
                $table->decimal('bonus_wagered', 15, 2);
            if (!Schema::hasColumn('user_bonus', 'created_at'))
                $table->dateTime('created_at');
            if (!Schema::hasColumn('user_bonus', 'updated_at'))
                $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
