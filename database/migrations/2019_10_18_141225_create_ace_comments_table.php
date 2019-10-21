<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcecommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ace_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->Integer('comment_from')->unsigned();
            $table->foreign('comment_from')->references('id')->on('users')->onDelete('cascade');
            $table->string('comment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return voidphp artisan migrate
     *
     */
    public function down()
    {
        Schema::dropIfExists('ace_comments');
    }
}
