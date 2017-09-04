<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserTransactionIdFieldToUserBonusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_bonus', function (Blueprint $table) {
            $table->integer('user_transaction_id')->unsigned()->nullable();

            $table->foreign('user_transaction_id')->references('id')->on('user_transactions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_bonus', function (Blueprint $table) {
            $table->dropForeign(['user_transaction_id']);

            $table->dropColumn('user_transaction_id');
        });
    }
}
