<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndicator41Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indicator_4_1', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('report_id');
            $table->foreign('report_id')
                ->references('id')->on('reports')
                ->onDelete('cascade');
            $table->string('programmetitle');
            $table->string('level');
            $table->string('typeofaccreditation');
            $table->string('accreditationreference');
            $table->string('accreditationagency');
            $table->string('agencyname');
            $table->string('agencyemail');
            $table->string('agencycontact');
            $table->date('dateofaccreditation');
            $table->date('exp_accreditationdate');
            $table->string('newly_accredited_programme');
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
        Schema::dropIfExists('indicator_4_1');
    }
}
