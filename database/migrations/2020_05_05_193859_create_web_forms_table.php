<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_forms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('form_name');
            $table->string('route_name')->nullable();
            $table->string('url')->nullable();
            $table->string('view_path')->nullable();
            $table->string('view_name');
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
        Schema::dropIfExists('web_forms');
    }
}
