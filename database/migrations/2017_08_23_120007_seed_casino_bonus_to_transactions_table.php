<?php

use Illuminate\Database\Migrations\Migration;

class SeedCasinoBonusToTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('transactions')->insert([
            'id' => 'casino_bonus',
            'name' => 'Casino Bonus'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('transactions')->whereIn('id', [
            'casino_bonus'
        ])->delete();
    }
}
