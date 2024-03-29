<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class SinglesMatch extends Model
{
    protected $fillable = ['tournament_id', 'bracket', 'winner', 'loser', 'score', 'winner_bracket_position', 'loser_bracket_position', 'score_input'];
}
