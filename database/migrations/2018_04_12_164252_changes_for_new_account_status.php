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
            $table->string('notes', 300)->nullable();
            $table->timestamps();
            $table->unique(['cc_number', 'passport_number']);

            $table->foreign('current_id')->references('id')->on('users');
        });
        Schema::table('user_profiles_log', function (Blueprint $table) {
            $table->integer('old_user_id')->unsigned()->nullable()->after('username');
            $table->integer('action_code')->unsigned()->nullable()->after('old_user_id');
            $table->integer('status_code')->unsigned()->nullable()->after('action_code');
            $table->text('descr_acao')->nullable()->after('status_code');
            $table->integer('duration')->unsigned()->nullable()->after('descr_acao');
            $table->text('motive')->nullable()->after('duration');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('original_date')->nullable();
            $table->timestamp('updated_at')->after('created_at');

            $table->foreign('old_user_id')->references('id')->on('users');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->integer('unique_id')->unsigned()->nullable()->after('id');
            $table->foreign('unique_id')->references('id')->on('unique_players');
        });
        Schema::table('user_bonus', function (Blueprint $table) {
            $table->integer('unique_id')->unsigned()->nullable()->after('user_id');
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_unique_id_foreign');

            $table->dropColumn('unique_id');
        });
        Schema::table('user_bonus', function (Blueprint $table) {
            $table->dropForeign('user_bonus_unique_id_foreign');

            $table->dropColumn('unique_id');
        });
        Schema::table('user_profiles_log', function (Blueprint $table) {
            $table->dropForeign('user_profiles_log_old_user_id_foreign');

            $table->dropColumn('old_user_id');
            $table->dropColumn('action_code');
            $table->dropColumn('status_code');
            $table->dropColumn('descr_acao');
            $table->dropColumn('motive');
            $table->dropColumn('duration');
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
            $table->dropColumn('original_date');
            $table->dropColumn('updated_at');
        });
        Schema::drop('unique_players');
    }
}
