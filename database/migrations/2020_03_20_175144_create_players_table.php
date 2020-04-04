<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('first_name');
            $table->string('last_name');
            $table->unsignedInteger('position');
            $table->string('class');
            $table->string('gender');
            $table->unsignedInteger('one_singles_rank');
            $table->unsignedInteger('two_singles_rank');
            $table->unsignedInteger('one_doubles_rank');
            $table->unsignedInteger('two_doubles_rank');
            $table->unsignedInteger('school_id')->default(0);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('players');
    }
}
