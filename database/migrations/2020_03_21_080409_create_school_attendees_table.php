<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolAttendeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_attendees', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedInteger('school_id');
            $table->unsignedInteger('tournament_id');

            //FIX LATER default should be 0
            $table->boolean('invite_accepted')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('school_attendees');
    }
}
