<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndicator51Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indicator_5_1', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('report_id');
            $table->foreign('report_id')
                ->references('id')->on('reports')
                ->onDelete('cascade');
            $table->double('amountindollars')->default(0);
            $table->double('originalamount')->default(0);
            $table->char('currency','9');
            $table->string('source');
            $table->date('datereceived');
            $table->string('region');
            $table->text('bankdetails');
            $table->string('fundingreason');
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
        Schema::dropIfExists('indicator_5_1');
    }
}
