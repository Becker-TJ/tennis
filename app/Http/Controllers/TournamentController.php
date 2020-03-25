<?php

namespace App\Http\Controllers;
use App\Tournament;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('auth');
//    }

    public function showCreateTournament() {
        return view('createtournament');
    }

    public function showTournaments() {
        $tournaments = Tournament::all();

        return view('tournaments', [
            'tournaments' => $tournaments
        ]);
    }

    public function create() {
        $data = $_POST;
        $tournament = new Tournament;

        $tournament['name'] = $data['tournament_name'];
        $tournament['location_name'] = $data['location_name'];
        $tournament['team_count'] = $data['team_count'];
        $tournament ['gender'] = $data['gender'];
        $tournament['address'] = $data['address'];
        $tournament ['host_id'] = 3;

        $tournament->saveOrFail();

        return view('createtournament');
    }

}
