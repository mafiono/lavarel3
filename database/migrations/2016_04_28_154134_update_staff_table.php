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
            $table->string('health_insurance');
            $table->string('owns_car');
            $table->unsignedTinyInteger('written_english');
            $table->unsignedTinyInteger('oral_english');
            $table->unsignedTinyInteger('computer_skills');
            $table->text('personal_interests');
            $table->text('vacations_activities');
            $table->text('software_experience');
            $table->string('upload_pc_declaration', 45);
            $table->string('upload_citizen_card', 45);
            $table->string('upload_curriculum_vitae', 45);
            $table->string('upload_qualifications_certificate', 45);
            $table->string('upload_nda', 45);
            $table->string('upload_work_contract', 45);
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
            $table->dropColumn('upload_pc_declaration');
            $table->dropColumn('upload_citizen_card');
            $table->dropColumn('upload_curriculum_vitae');
            $table->dropColumn('upload_qualifications_certificate');
            $table->dropColumn('upload_nda');
            $table->dropColumn('upload_work_contract');
        });
    }
}
