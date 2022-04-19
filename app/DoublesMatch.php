<?php



namespace App;


use Illuminate\Database\Eloquent\Model;
use DB;

class DoublesMatch extends Model
{
    protected $fillable = ['tournament_id','bracket', 'winner', 'loser', 'score','winner_bracket_position', 'loser_bracket_position', 'score_input'];
    protected $dates = ['created_at', 'updated_at'];

}
