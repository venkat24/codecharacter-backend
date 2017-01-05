<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('teamId')->unsigned();
            $table->integer('pragyanId')->unique();
            $table->string('name',255);
            $table->string('password',255);
            $table->string('emailId')->unique();
            $table->integer('phoneNo')->unsigned();
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
        Schema::drop('registrations');
    }
}
