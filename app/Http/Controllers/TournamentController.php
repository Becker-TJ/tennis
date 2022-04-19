<?php

namespace App\Http\Controllers;
use App\BracketPosition;
use App\SchoolAttendee;
use App\Tournament;
use App\School;
use App\Player;
use DemeterChain\B;
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
        $tournaments = Tournament::all()->sortBy('date');
        $schoolAttendees = SchoolAttendee::all();

        //FIX LATER the value at the end of the query for invitations should be the logged in school id
        $invitations = $schoolAttendees->where('school_id', '=', 80);
        $pendingInvitations = $invitations->where('invite_accepted', '=', 0);
        $acceptedInvitations = $invitations->where('invite_accepted', '=', 1);


        foreach($pendingInvitations as $invitation) {
            foreach($tournaments as $key => $tournament) {
                if ($tournament->id == $invitation->tournament_id) {
                    $tournament->pendingInvite = true;
                    $moveTournament = $tournament;
                    $tournaments->forget($key);
                    $tournaments->prepend($moveTournament);
                }
            }
        }

        foreach($acceptedInvitations as $invitation) {
            foreach($tournaments as $key => $tournament) {
                if ($tournament->id == $invitation->tournament_id) {
                    $tournament->acceptedInvite = true;
                    $moveTournament = $tournament;
                    $tournaments->forget($key);
                    $tournaments->prepend($moveTournament);
                }
            }
        }


        return view('tournaments', [
            'tournaments' => $tournaments,
            'schoolAttendees' => $schoolAttendees
        ]);
    }

    public function showTournament(Tournament $tournament) {
        $school = School::find($tournament->host_id);
        $schools = School::all();
//        $bracketPositions = BracketPosition::all()->where('tournament_id' ,'=', $tournament->id)->first();

        $attendees = SchoolAttendee::all()->where('tournament_id', '=', $tournament->id)->where('invite_accepted', '=', 1);
        $oneSinglesPlayers = Player::all()->where('position', '=', 1);


        $attendeeSchoolIDs = [];
        foreach($attendees as $attendee) {
            $attendeeSchoolIDs[] = $attendee->school_id;
        }

        $girlsOneSinglesPlayers = $oneSinglesPlayers
            ->where('gender', '=', 'Female')
            ->whereIn('school_id', $attendeeSchoolIDs)
            ->sortBy('girls_one_singles_rank');
//
//        if($bracketPositions == null) {
//            $bracketPositions = new BracketPosition();
//            $bracketPositions->tournament_id = $tournament->id;
//            $bracketPositions->bracket = 'girlsOneSingles';
//            $increment = 1;
//            foreach($girlsOneSinglesPlayers as $player) {
//                $seed = $increment . '_seed';
//                $bracketPositions->$seed = $player->id;
//                $increment++;
//            }
//            $bracketPositions->saveOrFail();
//        } else {
//            $bracketPositions = BracketPosition::all()->where('tournament_id', '=', $tournament->id)->where('bracket','=', 'girlsOneSingles')->first();
//        }
//        for ($increment = 1; $increment < (count($girlsOneSinglesPlayers) + 1); $increment++) {
//            foreach($girlsOneSinglesPlayers as $player) {
//                if($player->id == $bracketPositions->{$increment . '_seed'}) {
//                    $bracketPositions->{$increment . '_seed_name'} = $player->first_name . ' ' . $player->last_name;
//                    $bracketPositions->{$increment . '_seed_school'} = $player->getSchool()->name;
//                    break;
//                }
//            }
//        }
//        $bracketPositionTitles = $bracketPositions->getFillable();
//        array_shift($bracketPositionTitles);

//        foreach($bracketPositionTitles as $key => $title) {
//            if($bracketPositions->$title != 0) {
//                $player = $girlsOneSinglesPlayers->find($bracketPositions->$title);
//                $playerName = $player->first_name . ' ' . $player->last_name;
//                $bracketPositions->{$title . '_name'} = $playerName;
//            }
//        }


        return view('tournament', [
            'tournament' => $tournament,
            'school' => $school,
            'attendees' => $attendees,
            'schools' => $schools,
            'girlsOneSinglesPlayers' => $girlsOneSinglesPlayers,
//            'bracketPositions' => $bracketPositions
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

        $tournament ['host_id'] = 80;

        $tournament->saveOrFail();

        $schoolAttendee = new SchoolAttendee;
        $schoolAttendee->school_id = $tournament['host_id'];;
        $schoolAttendee->tournament_id = $tournament->id;
        $schoolAttendee->invite_accepted = 1;
        $schoolAttendee->saveOrFail();


        return redirect()->route('tournament', ['tournament' => $tournament->id]);

    }




}
