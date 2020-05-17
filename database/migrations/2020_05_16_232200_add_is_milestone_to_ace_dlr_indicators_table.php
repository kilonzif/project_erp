<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsMilestoneToAceDlrIndicatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ace_dlr_indicators', function (Blueprint $table) {
            $table->boolean('is_milestone')->default(0)->after('general_order');
            $table->smallInteger('master_parent_id')->default(0)->after('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ace_dlr_indicators', function (Blueprint $table) {
            $table->dropColumn(['is_milestone','master_parent_id']);
        });
    }
}
