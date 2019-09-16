<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndicatorOneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indicator_one', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->Integer('ace_id')->unsigned();
            $table->foreign('ace_id')->references('id')->on('aces')->onDelete('cascade');
            $table->string('requirement');
            $table->date('submission_date');
            $table->string('file_one')->nullable();
            $table->string('file_two')->nullable();
            $table->string('url')->nullable();
            $table->text('comments')->nullable();
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
        Schema::dropIfExists('indicator_one');
    }
}
