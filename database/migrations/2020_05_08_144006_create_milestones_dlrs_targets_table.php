<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMilestonesDlrsTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('milestones_dlrs_targets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('milestones_dlr_id');
            $table->text('target_indicator');
            $table->date('expected_timeline');
            $table->double('estimated_cost')->default(0)->unsigned();
            $table->double('disbursement_estimated_earning')->default(0)->unsigned();
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
        Schema::dropIfExists('milestones_dlrs_targets');
    }
}
