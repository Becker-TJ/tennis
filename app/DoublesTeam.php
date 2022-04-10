<?php



namespace App;


use Illuminate\Database\Eloquent\Model;
use DB;

class DoublesTeam extends Model
{
    protected $fillable = ['player_1', 'player_2'];
    protected $dates = ['created_at', 'updated_at'];

}
