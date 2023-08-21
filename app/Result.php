<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use App\Court;

class Result extends Model
{
    protected $table = 'results';
    protected $fillable = ['tournament_id', 'bracket', 'matchup', 'winner', 'loser', 'score'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function court()
    {
        return Court::where('tournament_id', $this->tournament_id)
            ->where('bracket', $this->bracket)
            ->where('matchup', $this->matchup)
            ->first();
    }
}
