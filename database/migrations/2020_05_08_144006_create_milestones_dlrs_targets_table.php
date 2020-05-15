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
        Schema::dropIfExists('milestones_dlrs_targets');
        Schema::create('milestones_dlrs_targets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('milestones_dlr_id');
            $table->text('target_indicator');
            $table->boolean('verification_status')->default(false);
            $table->boolean('status')->default(true);
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
