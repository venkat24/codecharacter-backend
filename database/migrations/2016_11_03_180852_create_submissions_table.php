<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('teamId')->unsigned();
            $table->dateTime('completedOn');
            $table->integer('levelNo');
            $table->enum('status', ['RUNNING', 'WAITING', 'COMPLETED','FAILED'])->default('WAITING');
            //$table->binary('submittedCode');
            $table->timestamps();
        });
        DB::statement('ALTER TABLE submissions ADD submittedCode MEDIUMBLOB');
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('submissions');
    }
}
