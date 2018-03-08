<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeUserNotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_notes', function (Blueprint $table){
            $table->string('department', 50)->default('Operations')->after('staff_id');
            $table->integer('priority')->detault(0)->after('department');
            $table->string('problem_type', 50)->nullable()->after('priority');
            $table->string('resolution', 500)->nullable()->after('note');
            $table->dateTime('due_date')->nullable()->after('resolution');
            $table->renameColumn('viewed', 'resolved');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_notes', function (Blueprint $table){
            $table->dropColumn('department');
            $table->dropColumn('priority');
            $table->dropColumn('problem_type');
            $table->dropColumn('resolution');
            $table->dropColumn('due_date');
            $table->renameColumn('resolved', 'viewed');
        });
    }
}
