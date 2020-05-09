<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMilestonesDlrsResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('milestones_dlrs_results', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('report_id');
            $table->foreign('report_id')
                ->references('id')->on('reports')
                ->onDelete('cascade');
            $table->unsignedInteger('milestones_dlrs_target_id');
            $table->text('document_1_file_path');
            $table->text('document_2_file_path')->nullable();
            $table->text('document_3_file_path')->nullable();
            $table->text('document_4_file_path')->nullable();
            $table->text('url_1');
            $table->text('url_2')->nullable();
            $table->text('url_3')->nullable();
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
        Schema::dropIfExists('milestones_dlrs_results');
    }
}
