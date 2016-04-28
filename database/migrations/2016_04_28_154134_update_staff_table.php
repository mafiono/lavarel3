<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff', function(Blueprint $table) {
            $table->renameColumn('telemovel', 'mobile');
            $table->renameColumn('morada', 'address');
            $table->string('email_personal', 45);
            $table->string('mobile_personal', 15);
            $table->string('marital_status', 10);
            $table->string('citizen_card', 15);
            $table->boolean('health_insurance');
            $table->boolean('owns_car');
            $table->unsignedTinyInteger('written_english');
            $table->unsignedTinyInteger('oral_english');
            $table->unsignedTinyInteger('computer_skills');
            $table->text('personal_interests');
            $table->text('vacations_activities');
            $table->text('software_experience');
            $table->string('pc_declaration_file', 45);
            $table->string('citizen_card_file', 45);
            $table->string('curriculum_vitae_file', 45);
            $table->string('qualifications_certificate_file', 45);
            $table->string('nda_file', 45);
            $table->string('work_contract_file', 45);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staff', function(Blueprint $table) {
            $table->renameColumn('mobile', 'telemovel');
            $table->renameColumn('address', 'morada');
            $table->dropColumn('email_personal');
            $table->dropColumn('mobile_personal');
            $table->dropColumn('marital_status');
            $table->dropColumn('citizen_card');
            $table->dropColumn('health_insurance');
            $table->dropColumn('owns_car');
            $table->dropColumn('written_english');
            $table->dropColumn('oral_english');
            $table->dropColumn('computer_skills');
            $table->dropColumn('personal_interests');
            $table->dropColumn('vacations_activities');
            $table->dropColumn('software_experience');
            $table->dropColumn('pc_declaration_file');
            $table->dropColumn('citizen_card_file');
            $table->dropColumn('curriculum_vitae_file');
            $table->dropColumn('qualifications_certificate_file');
            $table->dropColumn('nda_file');
            $table->dropColumn('work_contract_file');
        });
    }
}
