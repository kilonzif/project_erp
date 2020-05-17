<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('files_uploads');
        Schema::create('files_uploads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('ace_id')->nullable();
            $table->unsignedInteger('user_id');
            $table->string('file_one');
            $table->string('file_two')->nullable();
            $table->text('comments')->nullable();
            $table->string('file_category')->nullable();
            $table->string('file_one_path')->nullable();
            $table->string('file_two_path')->nullable();
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('files_uploads');
    }
}
