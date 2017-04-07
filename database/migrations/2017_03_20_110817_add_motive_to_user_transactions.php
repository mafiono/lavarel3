<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMotiveToUserTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_transactions', function (Blueprint $table) {
            $table->decimal('cost')->default(0)->after('tax');
            $table->string('motive', 1000)->nullable()->after('cost');
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
            $table->dropColumn('cost');
            $table->dropColumn('motive');
        });
    }
}
