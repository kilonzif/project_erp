<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDescriptionsToMilestonesDlrsResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('milestones_dlrs_results', function (Blueprint $table) {
            $table->boolean('document_1_description')->after('document_1_file_path');
            $table->boolean('document_2_description')->after('document_2_file_path')->nullable();
            $table->boolean('document_3_description')->after('document_3_file_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('milestones_dlrs_results', function (Blueprint $table) {
            $table->dropColumn(['document_1_description','document_2_description','document_3_description']);
        });
    }
}
