<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoublesTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doubles_teams', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedInteger('player_1');
            $table->unsignedInteger('player_2');
            $table->unsignedInteger('boys_one_doubles_rank')->default(99999);
            $table->unsignedInteger('boys_two_doubles_rank')->default(99999);
            $table->unsignedInteger('girls_one_doubles_rank')->default(99999);
            $table->unsignedInteger('girls_two_doubles_rank')->default(99999);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doubles_teams');
    }
}
