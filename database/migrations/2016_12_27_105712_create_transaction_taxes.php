<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionTaxes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('transactions_taxes');
        Schema::create('transactions_taxes', function (Blueprint $table) {
            $table->collation = 'utf8_general_ci';

            $table->increments('id');
            $table->enum('method', ['deposit', 'withdraw']);
            $table->string('transaction_id', 45);
            $table->string('type', 45);
            $table->integer('process_time_min');
            $table->integer('process_time_max');
            $table->decimal('tax', 6, 3);
            $table->integer('free_above');
            $table->integer('free_days');
            $table->integer('min');
            $table->integer('max');
            $table->integer('staff_id')->unsigned();
            $table->integer('staff_session_id')->unsigned();

            $table->timestamps();
        });

        Schema::table('transactions_taxes', function (Blueprint $table) {

            $table->foreign('staff_id')->references('id')->on('staff');
            $table->foreign('staff_session_id')->references('id')->on('staff_sessions');
            $table->unique(['method', 'transaction_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('transactions_taxes');
    }
}
