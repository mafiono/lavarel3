<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeBankAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_bank_accounts', function(Blueprint $table){
            $table->string('transfer_type_id', 45)->default('bank_transfer')->after('user_id');
            $table->string('iban', 250)->change();

            $table->foreign('transfer_type_id')->references('id')->on('transactions');
        });
        Schema::table('user_bank_accounts', function(Blueprint $table){
            $table->renameColumn('iban', 'identity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_bank_accounts', function(Blueprint $table){
            $table->dropForeign('user_bank_accounts_transfer_type_id_foreign');
            $table->dropColumn('transfer_type_id');
            $table->renameColumn('identity', 'iban');
        });
    }
}
