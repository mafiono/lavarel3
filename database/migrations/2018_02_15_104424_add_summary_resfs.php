<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSummaryResfs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('summary_resfs', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->date('date');
            $table->string('category', 30);
            $table->string('aptr_category', 10);
            $table->string('description', 30);
            $table->decimal('bet_amount', 15, 2)->default(0);
            $table->decimal('bet_amount_bonus', 15, 2)->default(0);
            $table->decimal('win_amount', 15, 2)->default(0);
            $table->decimal('win_amount_bonus', 15, 2)->default(0);

            $table->decimal('total_comissoes', 15, 2)->default(0);
            $table->decimal('total_ganhos', 15, 2)->default(0);
            $table->decimal('total_apostas', 15, 2)->default(0);
            $table->decimal('total_reembolsos', 15, 2)->default(0);

            $table->decimal('total_ggr', 15, 2)->default(0);

            $table->timestamps();

            $table->primary(['date', 'aptr_category']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('summary_resfs');
    }
}
