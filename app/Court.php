<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
    protected $fillable = ['tournament_id', 'bracket', 'matchup', 'court'];

    public function result()
    {
        // Define the reverse relationship with the Result model
        return $this->belongsTo(Result::class, ['tournament_id', 'bracket', 'matchup'], ['tournament_id', 'bracket', 'matchup']);
    }
}
