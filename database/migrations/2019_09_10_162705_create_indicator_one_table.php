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
            $table->Integer('aceId')->unsigned();
            $table->foreign('aceId')->references('id')->on('aces')->onDelete('cascade');
            $table->string('requirement');
            $table->timestamp('submission_date');
            $table->string('file_name');
            $table->string('url');
            $table->string('web_link');
            $table->boolean('finalised');
            $table->text('comments');
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
