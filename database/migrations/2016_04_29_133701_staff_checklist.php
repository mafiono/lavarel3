<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StaffChecklist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('staff_checklist'))
            Schema::create('staff_checklist', function(Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('staff_id')->unsigned();
                $table->boolean('is_form_filled');
                $table->boolean('is_job_description_updated');
                $table->boolean('has_staff_code');
                $table->string('email_sfpo');
                $table->string('trello_email');
                $table->string('has_ftp');
                $table->string('emails');
                $table->boolean('has_business_cards');
                $table->boolean('has_trello_training');
                $table->string('has_skype_sfpo');
                $table->boolean('has_pc_declaration');
                $table->boolean('has_citizen_card_photocopy');
                $table->boolean('has_curriculum_vitae');
                $table->boolean('has_qualifications_certificate');
                $table->boolean('has_nda');
                $table->boolean('has_work_contract');
                $table->boolean('has_email_signature');
                $table->boolean('has_set_permissions');
                $table->string('pc');
                $table->string('company_car');
                $table->dateTime('end_contract_at');
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('staff_checklist');
    }
}
