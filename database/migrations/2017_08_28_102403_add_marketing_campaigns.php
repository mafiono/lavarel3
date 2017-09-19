<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMarketingCampaigns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketing_campaigns', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('partner_id');
            $table->string('name', 50);
            $table->string('link', 250)->nullable();
            $table->timestamps();
        });

        Schema::table('ads', function (Blueprint $table) {
            $table->integer('campaign_id');
            $table->dateTime('start');
            $table->dateTime('end');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('marketing_campaign');
    }
}
