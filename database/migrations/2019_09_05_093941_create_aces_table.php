<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */



    public function up()
    {
        Schema::create('aces', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('name');
            $table->string('acronym');
            $table->unsignedInteger('institution_id');
            $table->string('contact');
            $table->string('email')->unique();
            $table->string('field');
            $table->tinyInteger('currency_id')->default('1');
            $table->integer('dlr')->default('0');
            $table->tinyInteger('milestone_no')->default('4');
            $table->string('contact_person');
            $table->char('person_number');
            $table->string('person_email');
            $table->string('position');
            $table->tinyInteger('active')->default('1');
            $table->string('ace_type');
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
        Schema::dropIfExists('aces');
    }
}
