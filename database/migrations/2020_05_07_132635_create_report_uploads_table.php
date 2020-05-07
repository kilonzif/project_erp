<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_uploads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('report_id');
            $table->foreign('report_id')
                ->references('id')->on('reports')
                ->onDelete('cascade');
            $table->string('file_name');
            $table->string('file_path');
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
        Schema::dropIfExists('report_uploads');
    }
}
