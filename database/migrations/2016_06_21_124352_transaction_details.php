<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TransactionDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('user_transactions', function(Blueprint $table){
            $table->text('transaction_details')->nullable()->after('api_transaction_id');
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
        Schema::table('user_transactions', function(Blueprint $table){
            $table->dropColumn(['transaction_details']);
        });
    }
}
