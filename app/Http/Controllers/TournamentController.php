<?php

namespace App\Http\Controllers;
use App\SchoolAttendee;
use App\Tournament;
use App\School;

class TournamentController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.school');
    }

    public function showCreateTournament() {
        return view('createtournament');
    }

    public function showEditTournament(Tournament $tournament) {
        $boys = '';
        $girls = '';
        $both = '';
        switch($tournament->gender) {
            case 'Boys':
                $boys = 'checked';
                break;
            case
                'Girls':
                $girls = 'checked';
                break;
            case
                'Both':
                $both = 'checked';
            default:
                break;
        }
//        if($tournament->gender == 'Boys') {
//            $boys = 'checked';
//        } else if ($tournament)

        return view('createtournament', [
            'tournament' => $tournament,
            'boys' => $boys,
            'girls'=> $girls,
            'both' => $both
        ]);
    }

    public function showBracket() {
        return view('bracket');
    }

    public function showTournaments() {
        $tournaments = Tournament::all();
        $schoolAttendees = SchoolAttendee::all();

        return view('tournaments', [
            'tournaments' => $tournaments,
            'schoolAttendees' => $schoolAttendees
        ]);
    }

    public function showTournament(Tournament $tournament) {
        $school = School::find($tournament->host_id);

        $attendees = SchoolAttendee::all()->where('tournament_id', '=', $tournament->id);
        $tj = 'hi';
        return view('tournament', [
            'tournament' => $tournament,
            'school' => $school,
            'attendees' => $attendees
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
