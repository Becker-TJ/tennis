<?php


namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class SeedToMatchupPosition extends Model
{
    protected $fillable = ['seed', 'matchup_position'];
}
