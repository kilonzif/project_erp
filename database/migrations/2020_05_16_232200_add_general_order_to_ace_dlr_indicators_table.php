<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGeneralOrderToAceDlrIndicatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ace_dlr_indicators', function (Blueprint $table) {
            $table->smallInteger('general_order')->unique()->nullable()->after('order');
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
            $table->dropColumn(['general_order']);
        });
    }
}
