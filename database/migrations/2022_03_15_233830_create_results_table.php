<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedInteger('tournament_id');
            $table->string('bracket');
            $table->unsignedInteger('matchup');
            $table->unsignedInteger('winner')->nullable();
            $table->unsignedInteger('loser')->nullable();
            $table->string('score')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('results');
    }
}
