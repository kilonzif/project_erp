<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndicator73Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indicator_7_3', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('report_id');
            $table->foreign('report_id')
                ->references('id')->on('reports')
                ->onDelete('cascade');
            $table->unsignedInteger('indicator_id');
            $table->string('institutionname');
            $table->string('typeofaccreditation');
            $table->string('accreditationagency');
            $table->string('accreditationreference')->nullable();
            $table->string('contactname');
            $table->string('contactemail');
            $table->string('contactphone');
            $table->date('dateofaccreditation');
            $table->date('exp_accreditationdate');
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
        Schema::dropIfExists('indicator_7_3');
    }
}
