<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDescriptionsToIndicator43Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('indicator_4_3', function (Blueprint $table) {
            $table->string('document_1_description')->after('document_1');
            $table->string('document_2_description')->after('document_2')->nullable();
            $table->string('document_3_description')->after('document_3')->nullable();
            $table->string('document_4_description')->after('document_4')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('indicator_4_3', function (Blueprint $table) {
            $table->dropColumn(['document_1_description','document_2_description','document_3_description',
                'document_4_description']);
        });
    }
}
