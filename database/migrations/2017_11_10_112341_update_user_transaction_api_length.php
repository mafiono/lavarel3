<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserTransactionApiLength extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_transactions', function (Blueprint $table){
            $table->string('api_transaction_id', 75)->nullable()->change();
            $table->integer('user_bonus_id')->unsigned()->nullable()->after('api_transaction_id');

            $table->index('api_transaction_id');
            $table->index('user_bonus_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_transactions', function (Blueprint $table){
            $table->string('api_transaction_id', 75)->nullable()->change();
            $table->dropColumn('user_bonus_id');

            $table->dropIndex('api_transaction_id');
            $table->dropIndex('user_bonus_id');
        });
    }
}
