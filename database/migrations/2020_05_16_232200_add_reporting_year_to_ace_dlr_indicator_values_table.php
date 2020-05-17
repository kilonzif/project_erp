<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReportingYearToAceDlrIndicatorValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ace_dlr_indicator_values', function (Blueprint $table) {
            $table->year('reporting_year')->default(2019)->after('ace_dlr_indicator_id');
            $table->unsignedInteger('ace_id')->after('id');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ace_dlr_indicator_values', function (Blueprint $table) {
            $table->dropColumn(['year']);
        });
    }
}
