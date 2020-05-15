<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreColumnsToMilstonesDlrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('milestones_dlrs', function (Blueprint $table) {
            $table->integer('ace_id')->after('id');
            $table->double('estimated_cost')->after('description')->default(0);
            $table->double('estimated_earning')->after('estimated_cost')->default(0);
            $table->date('start_expected_timeline')->after('estimated_cost')->nullable();
            $table->date('end_expected_timeline')->after('estimated_cost')->nullable();
            $table->boolean('status')->after('estimated_cost')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('milestones_dlrs', function (Blueprint $table) {
            $table->dropColumn(['ace_id', 'estimated_cost', 'estimated_earning','start_expected_timeline',
                'end_expected_timeline','status']);
        });
    }
}
