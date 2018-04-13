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
        Schema::create('unique_players', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('current_id')->unsigned();//chave estrangeira
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
            $table->string('notes', 300)->nullable();
            $table->timestamps();
            $table->unique(['cc_number', 'passport_number']);

            $table->foreign('current_id')->references('id')->on('users');
        });
        Schema::table('user_profiles_log', function (Blueprint $table) {
            $table->integer('old_user_id')->unsigned()->nullable()->after('username');//chave estrangeira
            $table->integer('action_code')->unsigned()->nullable()->after('old_user_id');
            $table->integer('status_code')->unsigned()->nullable()->after('action_code');
            $table->text('description')->nullable()->after('status_code');
            $table->text('motive')->nullable()->after('description');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('original_date')->nullable();

            $table->foreign('old_user_id')->references('id')->on('users');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->integer('unique_id')->unsigned()->nullable()->after('user_id');//chave estrangeira
            $table->foreign('unique_id')->references('id')->on('unique_players');
        });
        Schema::table('user_bonus', function (Blueprint $table) {
            $table->integer('unique_id')->unsigned()->nullable()->after('user_id');//chave estrangeira
            $table->foreign('unique_id')->references('id')->on('unique_players');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_profiles_log', function (Blueprint $table) {
            $table->dropColumn('old_user_id');
            $table->dropColumn('action_code');
            $table->dropColumn('status_code');
            $table->dropColumn('description');
            $table->dropColumn('motive');
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
            $table->dropColumn('original_date');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('unique_id');
        });

        Schema::table('user_bonus', function (Blueprint $table) {
            $table->dropColumn('unique_id');
        });

        Schema::drop('unique_players');
    }
}
