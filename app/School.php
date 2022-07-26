<?php

namespace App;

use App\DoublesTeam;
use App\Player;
use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address', 'conference'];

    public function getOneSinglesPlayer()
    {
        $player = Player::where('school_id', '=', $this->id)->where('position', '=', 1)->first();

        return $player;
    }

    public function getTwoSinglesPlayer()
    {
        $player = Player::where('school_id', '=', $this->id)->where('position', '=', 2)->first();

        return $player;
    }

    public function getGirlsOneDoublesTeam()
    {
        $players = [];
        $players[] = Player::where('school_id', '=', $this->id)->where('position', '=', 3)->where('gender', '=', 'Female')->first();
        $players[] = Player::where('school_id', '=', $this->id)->where('position', '=', 4)->where('gender', '=', 'Female')->first();

        return $players;
    }

    public function getGirlsTwoDoublesTeam()
    {
        $players = [];
        $players[] = Player::where('school_id', '=', $this->id)->where('position', '=', 5)->where('gender', '=', 'Female')->first();
        $players[] = Player::where('school_id', '=', $this->id)->where('position', '=', 6)->where('gender', '=', 'Female')->first();

        return $players;
    }

    public function getBoysOneDoublesTeam()
    {
        $players = [];
        $players[] = Player::where('school_id', '=', $this->id)->where('position', '=', 3)->where('gender', '=', 'Male')->first();
        $players[] = Player::where('school_id', '=', $this->id)->where('position', '=', 4)->where('gender', '=', 'Male')->first();

        return $players;
    }

    public function getBoysTwoDoublesTeam()
    {
        $players = [];
        $players[] = Player::where('school_id', '=', $this->id)->where('position', '=', 5)->where('gender', '=', 'Male')->first();
        $players[] = Player::where('school_id', '=', $this->id)->where('position', '=', 6)->where('gender', '=', 'Male')->first();

        return $players;
    }
}
