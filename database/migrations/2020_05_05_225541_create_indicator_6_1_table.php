<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndicator61Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indicator_6_1', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('report_id');
            $table->foreign('report_id')
                ->references('id')->on('reports')
                ->onDelete('cascade');
            $table->string('ifr_period')->nullable();
            $table->string('file_name_1')->nullable();
            $table->date('file_name_1_submission')->nullable();
            $table->string('efa_period')->nullable();
            $table->string('file_name_2')->nullable();
            $table->date('file_name_2_submission')->nullable();
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
        Schema::dropIfExists('indicator_6_1');
    }
}
