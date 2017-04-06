<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomToUserMails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_mails', function (Blueprint $table) {
            $table->integer('custom_id')->unsigned()->nullable()->after('tries');
            $table->string('custom_text')->nullable()->after('custom_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_mails', function (Blueprint $table) {
            $table->dropColumn('custom_id');
            $table->dropColumn('custom_text');
        });
    }
}
