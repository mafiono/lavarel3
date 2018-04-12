<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangesForNewAccountStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_profiles_log', function (Blueprint $table){
            $table->integer('old_user_id')->unsigned()->nullable()->after('username');
            $table->integer('action_code')->unsigned()->nullable()->after('username');
            $table->integer('status_code')->unsigned()->nullable()->after('username');
            $table->text('description')->nullable()->after('username');
            $table->text('motive')->nullable()->after('username');
        });
        if (!Schema::hasTable('unique_players'))
            Schema::create('unique_players', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('current_id')->unsigned();
                $table->integer('old_id')->unsigned()->nullable();
                $table->string('username', 45);
                $table->string('cc_number', 20)->nullable();
                $table->string('passport_number', 20)->nullable();
                $table->string('name', 100);
                $table->string('email', 100);
                $table->string('phone', 22)->nullable();
                $table->date('birth_date');
                $table->string('nationality', 2)->nullable();
                $table->string('address', 150)->nullable();
                $table->string('zip_code', 15)->nullable();
                $table->string('district', 50)->nullable();
                $table->string('city', 100)->nullable();
                $table->string('country', 2)->nullable();
                $table->string('tax_number', 20)->nullable();
                $table->string('notes',300)->nullable();
                $table->timestamps();
                $table->unique(['cc_number','passport_number']);
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_profiles_log', function (Blueprint $table){
            $table->dropColumn('old_user_id');
            $table->dropColumn('action_code');
            $table->dropColumn('status_code');
            $table->dropColumn('description');
            $table->dropColumn('motive');
        });

        Schema::drop('unique_players');
    }
}
