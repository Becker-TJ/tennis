<?php

namespace App\Http\Controllers;
use App\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class PlayerController extends Controller
{

    public function showPlayers() {
        $players = Player::all();

        return view('players', [
            'players' => $players
        ]);
    }

    public function showRoster() {
        $players = Player::all();

        return view('roster', [
            'players' => $players
        ]);
    }

    public function create() {
        $data = $_POST;
        $player = new Player;

        $player['name'] = $data['tournament_name'];
        $player['location_name'] = $data['location_name'];
        $player['team_count'] = $data['team_count'];
        $player ['gender'] = $data['gender'];
        $player['address'] = $data['address'];
        $player ['host_id'] = 3;

        $player->saveOrFail();

        return view('createtournament');
    }
}
