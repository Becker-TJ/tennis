<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Player;

class School extends Model
{
    protected $fillable = ['name', 'address', 'conference'];
    protected $dates = ['created_at', 'updated_at'];

    public function getOneSinglesPlayer() {
        $player = Player::where('school_id', '=', $this->id)->where('position','=', 1)->first();
        return $player;
    }
    public function getTwoSinglesPlayer() {
        $player = Player::where('school_id', '=', $this->id)->where('position','=', 2)->first();
        return $player;
    }
    public function getOneDoublesTeam() {
        $players = [];
        $players[] = Player::where('school_id', '=', $this->id)->where('position','=', 3)->first();
        $players[] = Player::where('school_id', '=', $this->id)->where('position','=', 4)->first();
        return $players;
    }
    public function getTwoDoublesTeam() {
        $players = [];
        $players[] = Player::where('school_id', '=', $this->id)->where('position','=', 5)->first();
        $players[] = Player::where('school_id', '=', $this->id)->where('position','=', 6)->first();
        return $players;
    }
}
