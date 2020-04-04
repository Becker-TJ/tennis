<?php

namespace App\Http\Controllers;
use App\SchoolAttendant;
use App\Tournament;

class TournamentController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.school');
    }

    public function showCreateTournament() {
        return view('createtournament');
    }

    public function showTournaments() {
        $tournaments = Tournament::all();
        $schoolAttendants = SchoolAttendant::all();

        return view('tournaments', [
            'tournaments' => $tournaments,
            'schoolAttendants' => $schoolAttendants
        ]);
    }

    public function create() {
        $data = $_POST;
        $tournament = new Tournament;

        $tournament['name'] = $data['tournament_name'];
        $tournament['location_name'] = $data['location_name'];
        $tournament['address'] = $data['address'];
        $tournament['date'] = $data['date'];
        $tournament['time'] = $data['time'];
        $tournament['team_count'] = $data['team_count'];
        $tournament['gender'] = $data['gender'];
        $tournament['level'] = $data['level'];
        $tournament['privacy_setting'] = $data['privacy_setting'];


        $tournament ['host_id'] = 3;

        $tournament->saveOrFail();

        return view('createtournament');
    }

}
