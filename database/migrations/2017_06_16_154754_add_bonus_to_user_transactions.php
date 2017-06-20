<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBonusToUserTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_transactions', function (Blueprint $table) {
            $table->decimal('credit_bonus', 15, 2)->default(0)->after('credit');
            $table->decimal('debit_bonus', 15, 2)->default(0)->after('credit_bonus');
            $table->decimal('initial_bonus', 15, 2)->default(0)->after('debit_bonus');
            $table->decimal('final_bonus', 15, 2)->default(0)->after('initial_bonus');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_transactions', function (Blueprint $table) {
            $table->dropColumn('credit_bonus');
            $table->dropColumn('debit_bonus');
            $table->dropColumn('initial_bonus');
            $table->dropColumn('final_bonus');
        });
    }
}
