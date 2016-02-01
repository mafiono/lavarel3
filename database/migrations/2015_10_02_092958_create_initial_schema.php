<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInitialSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /* Data Dictionaries */

        Schema::create('statuses', function (Blueprint $table) {
            $table->string('id', 45);
            $table->string('name', 45);
            $table->primary('id');
        });

        Schema::create('document_types', function (Blueprint $table) {
            $table->string('id', 45);
            $table->string('name', 45);
            $table->primary('id');
        });        

        Schema::create('user_roles', function (Blueprint $table) {
            $table->string('id', 45);
            $table->string('name', 45);
            $table->primary('id');
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->string('id', 45);
            $table->string('name', 45);
            $table->string('form_type', 45);
            $table->primary('id');
        });  

        Schema::create('self_exclusion_types', function (Blueprint $table) {
            $table->string('id', 45);
            $table->string('name', 45);
            $table->primary('id');
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->string('id', 45);
            $table->string('name', 45);
            $table->primary('id');
        });

        Schema::create('list_self_exclusions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('document_number', 15);
            $table->string('document_type_id', 45);
            $table->foreign('document_type_id')->references('id')->on('document_types');
            $table->datetime('start_date');
            $table->datetime('end_date');

            $table->timestamps();
        });  

        Schema::create('list_identity_checks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('tax_number');
            $table->datetime('birth_date');
            $table->tinyInteger('deceased');
            $table->tinyInteger('under_age');

            $table->timestamps();
        });                        

        /*
         * User Tables
         */
    
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', '45')->unique();
            $table->string('password', '60');
            $table->string('security_pin', '10');
            $table->tinyInteger('identity_checked')->default(false);
            $table->string('identity_method', '50')->nullable();
            $table->datetime('identity_date');
            $table->string('promo_code','100')->nullable();
            $table->string('currency','10')->nullable();
            $table->string('user_role_id', 45);
            $table->foreign('user_role_id')->references('id')->on('user_roles');

            $table->string('api_token', 100)->nullable();
            $table->string('api_password', 100)->nullable();

            $table->rememberToken();
            $table->timestamps();
        });   

        Schema::create('user_sessions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('session_number');
            $table->string('description', '100')->nullable();
            $table->string('ip', '100')->nullable();

            $table->timestamps();
        });

        Schema::create('user_limits', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->decimal('limit_deposit_daily', 8, 2)->default(0);
            $table->decimal('limit_deposit_weekly', 8, 2)->default(0);
            $table->decimal('limit_deposit_monthly', 8, 2)->default(0);
            $table->decimal('limit_betting_daily', 8, 2)->default(0);
            $table->decimal('limit_betting_weekly', 8, 2)->default(0);
            $table->decimal('limit_betting_monthly', 8, 2)->default(0);

            $table->integer('user_session_id')->unsigned();
            $table->foreign('user_session_id')->references('id')->on('user_sessions');

            $table->timestamps();
        });

        Schema::create('user_balances', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->decimal('balance_available', 8, 2)->default(0);
            $table->decimal('balance_bonus', 8, 2)->default(0);
            $table->decimal('balance_accounting', 8, 2)->default(0); 

            $table->integer('user_session_id')->unsigned();
            $table->foreign('user_session_id')->references('id')->on('user_sessions');

            $table->timestamps();
        });        

        Schema::create('user_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('status_id', 45);
            $table->foreign('status_id')->references('id')->on('statuses');
            $table->tinyInteger('current')->default(true);

            $table->integer('user_session_id')->unsigned();
            $table->foreign('user_session_id')->references('id')->on('user_sessions');

            $table->timestamps();
        });

        Schema::create('user_bank_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');            
            $table->string('bank_account', '100');
            $table->string('iban', '23');
            $table->tinyInteger('active')->default(true);

            $table->integer('user_session_id')->unsigned();
            $table->foreign('user_session_id')->references('id')->on('user_sessions');            

            $table->timestamps();
        });                  

        Schema::create('user_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');            
            $table->string('title', '15')->nullable();
            $table->string('name', '250');
            $table->string('email', '45')->unique();
            $table->datetime('birth_date');
            $table->string('nationality', '100')->nullable();
            $table->string('profession', '100')->nullable();
            $table->text('address');
            $table->string('zip_code', '15');
            $table->string('phone', '15');
            $table->string('city', '100');
            $table->string('country', '100');
            $table->string('document_number', 15)->unique();
            $table->string('document_type_id', 45);
            $table->foreign('document_type_id')->references('id')->on('document_types');
            $table->string('tax_number', 15);

            $table->integer('user_session_id')->unsigned();
            $table->foreign('user_session_id')->references('id')->on('user_sessions');            

            $table->timestamps();
        });

        Schema::create('user_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('settings_type_id', 45);
            $table->foreign('settings_type_id')->references('id')->on('settings');
            $table->tinyInteger('value');

            $table->integer('user_session_id')->unsigned();
            $table->foreign('user_session_id')->references('id')->on('user_sessions');            

            $table->timestamps();
        });

        Schema::create('user_documentation', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('file', 250);
            $table->string('description', 250);

            $table->integer('user_session_id')->unsigned();
            $table->foreign('user_session_id')->references('id')->on('user_sessions');            

            $table->timestamps();
        });

        Schema::create('user_self_exclusions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->datetime('request_date');
            $table->datetime('end_date');
            $table->string('self_exclusion_type_id', 45);
            $table->foreign('self_exclusion_type_id')->references('id')->on('self_exclusion_types');
            $table->string('status', 45);

            $table->integer('user_session_id')->unsigned();
            $table->foreign('user_session_id')->references('id')->on('user_sessions');            
            
            $table->timestamps();
        });  

        Schema::create('user_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('transaction_id', 45);
            $table->foreign('transaction_id')->references('id')->on('transactions');
            $table->integer('user_bank_account_id')->unsigned()->nullable();
            $table->foreign('user_bank_account_id')->references('id')->on('user_bank_accounts');
            $table->decimal('charge', 8, 2)->default(0);
            $table->decimal('credit', 8, 2)->default(0);
            $table->datetime('date');
            $table->tinyInteger('processed')->default(1);
            $table->text('description');

            $table->integer('user_session_id')->unsigned();
            $table->foreign('user_session_id')->references('id')->on('user_sessions');            
            
            $table->timestamps();
        });        

        Schema::create('user_bets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->tinyInteger('type');
            $table->tinyInteger('mode');
            $table->decimal('amount', 8, 2);
            $table->string('currency','10')->nullable();
            $table->decimal('amount_bonus', 8, 2)->nullable();
            $table->tinyInteger('result')->nullable();
            $table->string('sys_bet', 10)->nullable();
            $table->string('api_bet_id');
            $table->string('api_bet_type');

            $table->integer('user_session_id')->unsigned();
            $table->foreign('user_session_id')->references('id')->on('user_sessions');            
            
            $table->timestamps();
        });                        

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_bets');
        Schema::drop('user_transactions');
        Schema::drop('user_self_exclusions');
        Schema::drop('user_documentation');
        Schema::drop('user_settings');
        Schema::drop('user_limits');
        Schema::drop('user_balances');
        Schema::drop('user_statuses');
        Schema::drop('user_bank_accounts');
        Schema::drop('user_profiles');
        Schema::drop('user_sessions');
        Schema::drop('users');
        Schema::drop('statuses');
        Schema::drop('user_roles');
        Schema::drop('list_self_exclusions');
        Schema::drop('document_types');
        Schema::drop('settings');
        Schema::drop('self_exclusion_types');
        Schema::drop('transactions');
        Schema::drop('list_identity_checks');
    }
}
