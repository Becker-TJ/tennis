<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoublesMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doubles_matches', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedInteger('tournament_id');
            $table->string('bracket');
            $table->unsignedInteger('winner')->default(0);
            $table->unsignedInteger('loser')->default(0);
            $table->string('score')->nullable();
            $table->string('winner_bracket_position')->default("");
            $table->string('loser_bracket_position')->default("");
            $table->string('score_input');
            $table->unsignedInteger('court')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doubles_matches');
    }
}
