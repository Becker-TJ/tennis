<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBracketPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bracket_positions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedInteger('tournament_id');
            $table->string('bracket');
            $table->unsignedInteger('1_seed')->nullable();
            $table->unsignedInteger('2_seed')->nullable();
            $table->unsignedInteger('3_seed')->nullable();
            $table->unsignedInteger('4_seed')->nullable();
            $table->unsignedInteger('5_seed')->nullable();
            $table->unsignedInteger('6_seed')->nullable();
            $table->unsignedInteger('7_seed')->nullable();
            $table->unsignedInteger('8_seed')->nullable();
            $table->unsignedInteger('first_winners_round_one_top')->nullable();
            $table->unsignedInteger('first_winners_round_one_bottom')->nullable();
            $table->unsignedInteger('second_winners_round_one_top')->nullable();
            $table->unsignedInteger('second_winners_round_one_bottom')->nullable();
            $table->unsignedInteger('first_winners_round_two_top')->nullable();
            $table->unsignedInteger('first_winners_round_two_bottom')->nullable();
            $table->unsignedInteger('champion')->nullable();
            $table->unsignedInteger('first_consolation_round_one_top')->nullable();
            $table->unsignedInteger('first_consolation_round_one_bottom')->nullable();
            $table->unsignedInteger('second_consolation_round_one_top')->nullable();
            $table->unsignedInteger('second_consolation_round_one_bottom')->nullable();
            $table->unsignedInteger('first_consolation_round_two_top')->nullable();
            $table->unsignedInteger('first_consolation_round_two_bottom')->nullable();
            $table->unsignedInteger('consolation_champion')->nullable();
            $table->unsignedInteger('first_winners_lower_bracket_round_one_top')->nullable();
            $table->unsignedInteger('first_winners_lower_bracket_round_one_bottom')->nullable();
            $table->unsignedInteger('third_place')->nullable();
            $table->unsignedInteger('first_consolation_lower_bracket_round_one_top')->nullable();
            $table->unsignedInteger('first_consolation_lower_bracket_round_one_bottom')->nullable();
            $table->unsignedInteger('seventh_place')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bracket_positions');
    }
}
