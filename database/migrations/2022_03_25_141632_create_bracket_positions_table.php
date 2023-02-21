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
            $table->unsignedInteger('9_seed')->nullable();
            $table->unsignedInteger('10_seed')->nullable();
            $table->unsignedInteger('11_seed')->nullable();
            $table->unsignedInteger('12_seed')->nullable();
            $table->unsignedInteger('13_seed')->nullable();
            $table->unsignedInteger('14_seed')->nullable();
            $table->unsignedInteger('15_seed')->nullable();
            $table->unsignedInteger('16_seed')->nullable();
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
            $table->unsignedInteger('third-winners-round-one-top')->nullable();
            $table->unsignedInteger('third-winners-round-one-bottom')->nullable();
            $table->unsignedInteger('fourth-winners-round-one-top')->nullable();
            $table->unsignedInteger('fourth-winners-round-one-bottom')->nullable();
            $table->unsignedInteger('second-winners-round-two-top')->nullable();
            $table->unsignedInteger('second-winners-round-two-bottom')->nullable();
            $table->unsignedInteger('first-winners-round-three-top')->nullable();
            $table->unsignedInteger('first-winners-round-three-bottom')->nullable();
            $table->unsignedInteger('third-consolation-round-one-top')->nullable();
            $table->unsignedInteger('third-consolation-round-one-bottom')->nullable();
            $table->unsignedInteger('fourth-consolation-round-one-top')->nullable();
            $table->unsignedInteger('fourth-consolation-round-one-bottom')->nullable();
            $table->unsignedInteger('second-consolation-round-two-top')->nullable();
            $table->unsignedInteger('second-consolation-round-two-bottom')->nullable();
            $table->unsignedInteger('third-consolation-round-two-top')->nullable();
            $table->unsignedInteger('third-consolation-round-two-bottom')->nullable();
            $table->unsignedInteger('fourth-consolation-round-two-top')->nullable();
            $table->unsignedInteger('fourth-consolation-round-two-bottom')->nullable();
            $table->unsignedInteger('first-consolation-round-three-top')->nullable();
            $table->unsignedInteger('first-consolation-round-three-bottom')->nullable();
            $table->unsignedInteger('second-consolation-round-three-top')->nullable();
            $table->unsignedInteger('second-consolation-round-three-bottom')->nullable();
            $table->unsignedInteger('first-consolation-round-four-top')->nullable();
            $table->unsignedInteger('first-consolation-round-four-bottom')->nullable();
            $table->unsignedInteger('fifth-place')->nullable();
            $table->unsignedInteger('seventh-place')->nullable();
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
