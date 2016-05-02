<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Staff extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff', function(Blueprint $table) {
            if (Schema::hasColumns('staff', [
                'telemovel',
                'morada'
            ])) {
                $table->renameColumn('telemovel', 'mobile');
                $table->renameColumn('morada', 'address');
            }

            if (!Schema::hasColumn('staff', 'email_personal'))
                $table->string('email_personal', 45);
            if (!Schema::hasColumn('staff', 'mobile_personal'))
                $table->string('mobile_personal', 15);
            if (!Schema::hasColumn('staff', 'marital_status'))
                $table->string('marital_status', 10);
            if (!Schema::hasColumn('staff', 'citizen_card'))
                $table->string('citizen_card', 15);
            if (!Schema::hasColumn('staff', 'health_insurance'))
                $table->string('health_insurance');
            if (!Schema::hasColumn('staff', 'owns_car'))
                $table->string('owns_car');
            if (!Schema::hasColumn('staff', 'written_english'))
                $table->unsignedTinyInteger('written_english');
            if (!Schema::hasColumn('staff', 'oral_english'))
                $table->unsignedTinyInteger('oral_english');
            if (!Schema::hasColumn('staff', 'computer_skills'))
                $table->unsignedTinyInteger('computer_skills');
            if (!Schema::hasColumn('staff', 'personal_interests'))
                $table->text('personal_interests');
            if (!Schema::hasColumn('staff', 'vacations_activities'))
                $table->text('vacations_activities');
            if (!Schema::hasColumn('staff', 'software_experience'))
                $table->text('software_experience');
            if (!Schema::hasColumn('staff', 'upload_pc_declaration'))
                $table->string('upload_pc_declaration', 45);
            if (!Schema::hasColumn('staff', 'upload_citizen_card'))
                $table->string('upload_citizen_card', 45);
            if (!Schema::hasColumn('staff', 'upload_curriculum_vitae'))
                $table->string('upload_curriculum_vitae', 45);
            if (!Schema::hasColumn('staff', 'upload_qualifications_certificate'))
                $table->string('upload_qualifications_certificate', 45);
            if (!Schema::hasColumn('staff', 'upload_nda'))
                $table->string('upload_nda', 45);
            if (!Schema::hasColumn('staff', 'upload_work_contract'))
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
    }
}
