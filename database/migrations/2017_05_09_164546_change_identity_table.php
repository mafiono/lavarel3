<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeIdentityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('list_identity_checks', function (Blueprint $table) {
            $table->string('name', 100)->change()->after('id');
            $table->string('id_cidadao', 20)->after('name');
            $table->string('tax_number', 9)->nullable()->change()->after('id_cidadao');
            $table->date('birth_date')->change()->after('id_cidadao');
            $table->boolean('valido')->default(0)->after('under_age');
            $table->text('response')->nullable()->after('valido');

            $table->index(['name', 'id_cidadao', 'birth_date'], 'list_identity_checks_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('list_identity_checks', function (Blueprint $table) {
            $table->dropIndex('list_identity_checks_index');

            $table->string('name', 100)->change()->after('id');
            $table->string('tax_number', 9)->nullable()->change()->after('id_cidadao');
            $table->date('birth_date')->change()->after('id_cidadao');
            $table->string('name', 100)->change()->after('id');
            $table->dropColumn('id_cidadao');
            $table->dropColumn('valido');
            $table->dropColumn('response');
        });
    }
}
