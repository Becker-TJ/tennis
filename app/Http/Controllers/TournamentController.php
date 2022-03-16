<?php

namespace App\Http\Controllers;
use App\SchoolAttendee;
use App\Tournament;
use App\School;
use App\Player;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.school');
    }

    public function showCreateTournament() {
        $boysCheck = 'checked';
        $boysActive = 'active';
        $girlsCheck = '';
        $girlsActive = '';
        $bothCheck = '';
        $bothActive = '';

        $varsityCheck = 'checked';
        $varsityActive = 'active';
        $jvCheck = '';
        $jvActive = '';
        $jhCheck = '';
        $jhActive = '';

        $publicCheck = 'checked';
        $publicActive = 'active';
        $privateCheck = '';
        $privateActive = '';

        $createOrEdit = 'Create';
        $submitButtonText = 'Create Tournament';

        return view('createtournament', [
            'boysCheck' => $boysCheck,
            'girlsCheck'=> $girlsCheck,
            'bothCheck' => $bothCheck,
            'boysActive' => $boysActive,
            'girlsActive' => $girlsActive,
            'bothActive' => $bothActive,

            'varsityCheck' => $varsityCheck,
            'varsityActive' => $varsityActive,
            'jvCheck' => $jvCheck,
            'jvActive' => $jvActive,
            'jhCheck' => $jhCheck,
            'jhActive' => $jhActive,

            'publicCheck' => $publicCheck,
            'publicActive' => $publicActive,
            'privateCheck' => $privateCheck,
            'privateActive' => $privateActive,

            'createOrEdit' => $createOrEdit,
            'submitButtonText' => $submitButtonText
        ]);
    }

    public function showEditTournament(Tournament $tournament) {
        $boysCheck = 'checked';
        $boysActive = 'active';
        $girlsCheck = '';
        $girlsActive = '';
        $bothCheck = '';
        $bothActive = '';

        $varsityCheck = 'checked';
        $varsityActive = 'active';
        $jvCheck = '';
        $jvActive = '';
        $jhCheck = '';
        $jhActive = '';

        $publicCheck = 'checked';
        $publicActive = 'active';
        $privateCheck = '';
        $privateActive = '';

        $createOrEdit = 'Edit';
        $submitButtonText = 'Save Changes';

        if($tournament->gender == 'Girls') {
            $girlsCheck = 'checked';
            $girlsActive = 'active';
            $boysCheck = '';
            $boysActive = '';
        } else if($tournament->gender == 'Both') {
            $bothCheck = 'checked';
            $bothActive = 'active';
            $boysCheck = '';
            $boysActive = '';
        }

        if($tournament->level == 'Junior Varsity') {
            $jvCheck = 'checked';
            $jvActive = 'active';
            $varsityCheck = '';
            $varsityActive = '';
        } else if ($tournament->level == 'Junior High') {
            $jhCheck = 'checked';
            $jhActive = 'active';
            $varsityCheck = '';
            $varsityActive = '';
        }

        if ($tournament->privacy_setting == 'Private') {
            $privateCheck = 'checked';
            $privateActive = 'active';
            $publicCheck = '';
            $publicActive = '';
        }

        return view('createtournament', [
            'tournament' => $tournament,
            'boysCheck' => $boysCheck,
            'girlsCheck'=> $girlsCheck,
            'bothCheck' => $bothCheck,
            'boysActive' => $boysActive,
            'girlsActive' => $girlsActive,
            'bothActive' => $bothActive,

            'varsityCheck' => $varsityCheck,
            'varsityActive' => $varsityActive,
            'jvCheck' => $jvCheck,
            'jvActive' => $jvActive,
            'jhCheck' => $jhCheck,
            'jhActive' => $jhActive,

            'publicCheck' => $publicCheck,
            'publicActive' => $publicActive,
            'privateCheck' => $privateCheck,
            'privateActive' => $privateActive,

            'createOrEdit' => $createOrEdit,
            'submitButtonText' => $submitButtonText
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
        $schools = School::all();
        $attendees = SchoolAttendee::all()->where('tournament_id', '=', $tournament->id);

        $attendeeSchoolIDs = [];
        foreach($attendees as $attendee) {
            $attendeeSchoolIDs[] = $attendee->school_id;
        }

        $girlsOneSinglesPlayers = Player::all()->where('position', '=', 1)
            ->where('gender', '=', 'Female')
            ->whereIn('school_id', $attendeeSchoolIDs)
            ->sortBy('one_singles_rank');

        $girlsTwoSinglesPlayers = Player::all()->where('position', '=', 2)
            ->where('gender', '=', 'Female')
            ->whereIn('school_id', $attendeeSchoolIDs)
            ->sortBy('two_singles_rank');

        $boysOneSinglesPlayers = Player::all()->where('position', '=', 1)
            ->where('gender', '=', 'Male')
            ->whereIn('school_id', $attendeeSchoolIDs)
            ->sortBy('one_singles_rank');

        $boysTwoSinglesPlayers = Player::all()->where('position', '=', 2)
            ->where('gender', '=', 'Male')
            ->whereIn('school_id', $attendeeSchoolIDs)
            ->sortBy('two_singles_rank');

        return view('tournament', [
            'tournament' => $tournament,
            'school' => $school,
            'attendees' => $attendees,
            'schools' => $schools,
            'girlsOneSinglesPlayers' => $girlsOneSinglesPlayers,
            'girlsTwoSinglesPlayers' => $girlsTwoSinglesPlayers,
            'boysOneSinglesPlayers' => $girlsOneSinglesPlayers,
            'boysTwoSinglesPlayers' => $girlsTwoSinglesPlayers
        ]);

    }

    public function edit(Request $request) {
        $data = $_POST;
        $tournamentID = substr($request->session('attributes')->previousUrl(), 31);

        $tournament = Tournament::find($tournamentID);

        $tournament['name'] = $data['tournament_name'];
        $tournament['location_name'] = $data['location_name'];
        $tournament['address'] = $data['address'];
        $tournament['date'] = $data['date'];
        $tournament['time'] = $data['time'];
        $tournament['team_count'] = $data['team_count'];
        $tournament['gender'] = $data['gender'];
        $tournament['level'] = $data['level'];
        $tournament['privacy_setting'] = $data['privacy_setting'];
//        $tournament['host_id'] = 5;

        $tournament->saveOrFail();

        return view('createtournament');
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

        return redirect()->route('tournament', ['tournament' => $tournament->id]);
//        return view('createtournament');
    }


}
