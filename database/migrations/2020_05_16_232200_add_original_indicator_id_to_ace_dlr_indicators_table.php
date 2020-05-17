<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOriginalIndicatorIdToAceDlrIndicatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ace_dlr_indicators', function (Blueprint $table) {
            $table->smallInteger('original_indicator_id')->nullable(0)->after('is_milestone');
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
            $table->dropColumn(['original_indicator_id']);
        });
    }
}
