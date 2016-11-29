<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignkeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        Schema::table('notifications',function(Blueprint $table){
            $table->foreign('teamId')->references('id')->on('teams');
        });
        Schema::table('submissions',function(Blueprint $table){
            $table->foreign('teamId')->references('id')->on('teams');
        });
        Schema::table('registrations',function(Blueprint $table){
            $table->foreign('teamId')->references('id')->on('teams');
        });
        Schema::table('teams',function(Blueprint $table){
            $table->foreign('leaderRegistrationId')->references('id')->on('registrations');
        });
        Schema::table('invites',function(Blueprint $table){
            $table->foreign('fromTeamId')->references('id')->on('teams');
            $table->foreign('toRegistrationId')->references('id')->on('registrations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
