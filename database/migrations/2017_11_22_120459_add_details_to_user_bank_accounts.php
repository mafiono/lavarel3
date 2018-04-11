<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDetailsToUserBankAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_bank_accounts', function (Blueprint $table){
            $table->text('account_details')->nullable()->after('identity');
            $table->boolean('account_ready')->default(1)->after('status_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_bank_accounts', function (Blueprint $table){
            $table->dropColumn('account_details');
            $table->dropColumn('account_ready');
        });
    }
}
