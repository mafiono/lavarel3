<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AddWhatsappToUserSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_settings', function (Blueprint $table){
            $table->boolean('whatsapp')->default(1)->after('phone');
            $table->boolean('consent')->default(0)->after('user_id');
        });
        DB::table('session_types')->insert(['id' => 'give.consent']);
    }

        /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_settings', function (Blueprint $table){
            $table->dropColumn('whatsapp');
            $table->dropColumn('consent');
        });
        DB::table('session_types')->where('id', '=', 'give.consent')->delete();
    }
}
