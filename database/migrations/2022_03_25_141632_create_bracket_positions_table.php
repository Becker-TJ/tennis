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
            $table->unsignedInteger('1_seed')->default(0);
            $table->unsignedInteger('2_seed')->default(0);
            $table->unsignedInteger('3_seed')->default(0);
            $table->unsignedInteger('4_seed')->default(0);
            $table->unsignedInteger('5_seed')->default(0);
            $table->unsignedInteger('6_seed')->default(0);
            $table->unsignedInteger('7_seed')->default(0);
            $table->unsignedInteger('8_seed')->default(0);
            $table->unsignedInteger('first_winners_round_one_top')->default(0);
            $table->unsignedInteger('first_winners_round_one_bottom')->default(0);
            $table->unsignedInteger('second_winners_round_one_top')->default(0);
            $table->unsignedInteger('second_winners_round_one_bottom')->default(0);
            $table->unsignedInteger('first_winners_round_two_top')->default(0);
            $table->unsignedInteger('first_winners_round_two_bottom')->default(0);
            $table->unsignedInteger('champion')->default(0);
            $table->unsignedInteger('first_consolation_round_one_top')->default(0);
            $table->unsignedInteger('first_consolation_round_one_bottom')->default(0);
            $table->unsignedInteger('second_consolation_round_one_top')->default(0);
            $table->unsignedInteger('second_consolation_round_one_bottom')->default(0);
            $table->unsignedInteger('first_consolation_round_two_top')->default(0);
            $table->unsignedInteger('first_consolation_round_two_bottom')->default(0);
            $table->unsignedInteger('consolation_champion')->default(0);
            $table->unsignedInteger('first_winners_lower_bracket_round_one_top')->default(0);
            $table->unsignedInteger('first_winners_lower_bracket_round_one_bottom')->default(0);
            $table->unsignedInteger('third_place')->default(0);
            $table->unsignedInteger('first_consolation_lower_bracket_round_one_top')->default(0);
            $table->unsignedInteger('first_consolation_lower_bracket_round_one_bottom')->default(0);
            $table->unsignedInteger('seventh_place')->default(0);
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
