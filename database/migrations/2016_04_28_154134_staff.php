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

            if (!Schema::hasColumn('staff', 'email_personal'))
                $table->string('email_personal', 45);
            if (!Schema::hasColumn('staff', 'mobile_personal'))
                $table->string('mobile_personal', 15);
            if (!Schema::hasColumn('staff', 'marital_status'))
                $table->string('marital_status', 10);
            if (!Schema::hasColumn('staff', 'citizen_card'))
                $table->string('citizen_card', 15);
            if (!Schema::hasColumn('staff', 'owns_car'))
                $table->string('owns_car');
            if (!Schema::hasColumn('staff', 'written_english'))
                $table->unsignedTinyInteger('written_english');
            if (!Schema::hasColumn('staff', 'oral_english'))
                $table->unsignedTinyInteger('oral_english');
            if (!Schema::hasColumn('staff', 'computer_skills'))
                $table->unsignedTinyInteger('computer_skills');
            if (!Schema::hasColumn('staff', 'health_insurance'))
                $table->unsignedTinyInteger('health_insurance');

            if (!Schema::hasColumn('staff', 'personal_interests'))
                $table->text('personal_interests');
            if (!Schema::hasColumn('staff', 'vacations_activities'))
                $table->text('vacations_activities');

            if (!Schema::hasColumn('staff', 'has_office'))
                $table->boolean('has_office');
            if (!Schema::hasColumn('staff', 'has_phpstorm'))
                $table->boolean('has_phpstorm');
            if (!Schema::hasColumn('staff', 'has_webstorm'))
                $table->boolean('has_webstorm');
            if (!Schema::hasColumn('staff', 'has_photoshop'))
                $table->boolean('has_photoshop');
            if (!Schema::hasColumn('staff', 'has_coreldraw'))
                $table->boolean('has_coreldraw');
            if (!Schema::hasColumn('staff', 'has_windows'))
                $table->boolean('has_windows');
            if (!Schema::hasColumn('staff', 'has_osx'))
                $table->boolean('has_osx');

            if (!Schema::hasColumn('staff', 'upload_pc_declaration'))
                $table->string('upload_pc_declaration');
            if (!Schema::hasColumn('staff', 'upload_citizen_card'))
                $table->string('upload_citizen_card');
            if (!Schema::hasColumn('staff', 'upload_curriculum_vitae'))
                $table->string('upload_curriculum_vitae');
            if (!Schema::hasColumn('staff', 'upload_qualifications_certificate'))
                $table->string('upload_qualifications_certificate');
            if (!Schema::hasColumn('staff', 'upload_nda'))
                $table->string('upload_nda');
            if (!Schema::hasColumn('staff', 'upload_work_contract'))
                $table->string('upload_work_contract');
            if (!Schema::hasColumn('staff', 'upload_email_signature'))
                $table->string('upload_email_signature');

            // Update section

            if (Schema::hasColumn('staff', 'telemovel'))
                $table->renameColumn('telemovel', 'mobile');
            if (Schema::hasColumn('staff', 'morada'))
                $table->renameColumn('morada', 'address');

            if (Schema::hasColumn('staff', 'photo'))
                $table->string('photo')->change();
            if (Schema::hasColumn('staff', 'upload_pc_declaration'))
                $table->string('upload_pc_declaration')->change();
            if (Schema::hasColumn('staff', 'upload_citizen_card'))
                $table->string('upload_citizen_card')->change();
            if (Schema::hasColumn('staff', 'upload_curriculum_vitae'))
                $table->string('upload_curriculum_vitae')->change();
            if (Schema::hasColumn('staff', 'upload_qualifications_certificate'))
                $table->string('upload_qualifications_certificate')->change();
            if (Schema::hasColumn('staff', 'upload_nda'))
                $table->string('upload_nda')->change();
            if (Schema::hasColumn('staff', 'upload_work_contract'))
                $table->string('upload_work_contract')->change();

            if (Schema::hasColumn('staff', 'health_insurance'))
                $table->integer('health_insurance')->unsigned()->change();

            if (Schema::hasColumn('staff', 'software_experience'))
                $table->dropColumn('software_experience');

            if (!Schema::hasColumn('staff', 'ip_control'))
                $table->boolean('ip_control');

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
