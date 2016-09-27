<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateListSelfExcluded extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('list_self_exclusions', function(Blueprint $table){
            $table->integer('doc_type_id')->after('id');
            $table->string('nation_id')->after('document_number');
            $table->integer('confirmed')->after('end_date');
            $table->boolean('changed')->after('confirmed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('list_self_exclusions', function(Blueprint $table){
            $table->dropColumn('doc_type_id');
            $table->dropColumn('nation_id');
            $table->dropColumn('confirmed');
            $table->dropColumn('changed');
        });
    }
}
