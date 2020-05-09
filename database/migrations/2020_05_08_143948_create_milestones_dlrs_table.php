<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMilestonesDlrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('milestones_dlrs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('indicator_id');
            $table->foreign('indicator_id')
                ->references('id')->on('indicators')
                ->onDelete('cascade');
            $table->tinyInteger('milestone_no')->default(0)->unsigned();
            $table->text('description');
            $table->double('total_amount')->default(0)->unsigned();
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
        Schema::dropIfExists('milestones_dlrs');
    }
}
