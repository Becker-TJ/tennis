<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class BracketPosition extends Model
{
    protected $fillable = [
        'tournament_id',
        'bracket',
        'first_winners_round_one_top',
        'first_winners_round_one_bottom',
        'second_winners_round_one_top',
        'second_winners_round_one_bottom',
        'first_winners_round_two_top',
        'first_winners_round_two_bottom',
        'champion',
        'first_consolation_round_one_top',
        'first_consolation_round_one_bottom',
        'second_consolation_round_one_top',
        'second_consolation_round_one_bottom',
        'first_consolation_round_two_top',
        'first_consolation_round_two_bottom',
        'consolation_champion',
        'first_winners_lower_bracket_round_one_top',
        'first_winners_lower_bracket_round_one_bottom',
        'third_place',
        'first_consolation_lower_bracket_round_one_top',
        'first_consolation_lower_bracket_round_one_bottom',
        'seventh_place',
    ];

    public $matchupAssociations = [
        '1-seed' => '8-seed',
        '2-seed' => '7-seed',
        '3-seed' => '6-seed',
        '4-seed' => '5-seed',
        '5-seed' => '4-seed',
        '6-seed' => '3-seed',
        '7-seed' => '2-seed',
        '8-seed' => '1-seed'
    ];

    public $winningPathAssociations = [
        '1-seed' => 'first-winners-round-one-top',
        '2-seed' => 'second-winners-round-one-bottom',
        '3-seed' => 'second-winners-round-one-top',
        '4-seed' => 'first-winners-round-one-bottom',
        '5-seed' => 'first-winners-round-one-bottom',
        '6-seed' => 'second-winners-round-one-top',
        '7-seed' => 'second-winners-round-one-bottom',
        '8-seed' => 'first-winners-round-one-top',
        'first-winners-round-one-top' => 'first-winners-round-two-top',
        'first-winners-round-one-bottom' => 'first-winners-round-two-top',
        'second-winners-round-one-top' => 'first-winners-round-two-bottom',
        'second-winners-round-one-bottom' => 'first-winners-round-two-bottom',
        'first-consolation-round-one-top' => 'first-consolation-round-two-top',
        'first-consolation-round-one-bottom' => 'first-consolation-round-two-top',
        'second-consolation-round-one-top' => 'first-consolation-round-two-bottom',
        'second-consolation-round-one-bottom' => 'first-consolation-round-two-bottom',
        'first-winners-round-two-top' => 'champion',
        'first-winners-round-two-bottom' => 'champion',
        'first-consolation-round-two-top' => 'consolation-champion',
        'first-consolation-round-two-bottom' => 'consolation-champion',
        'first-winners-lower-bracket-round-one-top' => 'third-place',
        'first-winners-lower-bracket-round-one-bottom' => 'third-place',
        'first-consolation-lower-bracket-round-one-top' => 'seventh-place',
        'first-consolation-lower-bracket-round-one-bottom' => 'seventh-place'
    ];

    public $losingPathAssociations = [
        '1-seed' => 'first-consolation-round-one-top',
        '2-seed' => 'second-consolation-round-one-bottom',
        '3-seed' => 'second-consolation-round-one-top',
        '4-seed' => 'first-consolation-round-one-bottom',
        '5-seed' => 'first-consolation-round-one-bottom',
        '6-seed' => 'second-consolation-round-one-top',
        '7-seed' => 'second-consolation-round-one-bottom',
        '8-seed' => 'first-consolation-round-one-top',
        'first-consolation-round-one-top' => 'first-consolation-lower-bracket-round-one-top',
        'first-consolation-round-one-bottom' => 'first-consolation-lower-bracket-round-one-top',
        'second-consolation-round-one-top' => 'first-consolation-lower-bracket-round-one-bottom',
        'second-consolation-round-one-bottom' => 'first-consolation-lower-bracket-round-one-bottom',
        'first-winners-round-one-top' => 'first-winners-lower-bracket-round-one-top',
        'first-winners-round-one-bottom' => 'first-winners-lower-bracket-round-one-top',
        'second-winners-round-one-top' => 'first-winners-lower-bracket-round-one-bottom',
        'second-winners-round-one-bottom' => 'first-winners-lower-bracket-round-one-bottom'
    ];
}
