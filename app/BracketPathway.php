<?php

namespace App;


use DB;
use Illuminate\Database\Eloquent\Model;

class BracketPathway extends Model
{
    protected $table = 'bracket_pathways';
    protected $fillable = [
        'bracket_type',
        'matchup',
        'winning_path',
        'losing_path'
    ];
}
