<?php


namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

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
        'seventh_place'
    ];
    protected $dates = ['created_at', 'updated_at'];




}
