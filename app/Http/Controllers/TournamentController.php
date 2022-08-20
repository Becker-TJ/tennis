<?php

namespace App\Http\Controllers;

use App\BracketPosition;
use App\DoublesMatch;
use App\Player;
use App\School;
use App\SchoolAttendee;
use App\SinglesMatch;
use App\Tournament;
use Auth;
use DemeterChain\B;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    public function __construct()
    {
//        $this->middleware('check.school');
    }

    public function showCreateTournament()
    {
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
            'submitButtonText' => $submitButtonText,
        ]);
    }

    public function showEditTournament(Tournament $tournament)
    {
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

        if ($tournament->gender == 'Girls') {
            $girlsCheck = 'checked';
            $girlsActive = 'active';
            $boysCheck = '';
            $boysActive = '';
        } elseif ($tournament->gender == 'Both') {
            $bothCheck = 'checked';
            $bothActive = 'active';
            $boysCheck = '';
            $boysActive = '';
        }

        if ($tournament->level == 'Junior Varsity') {
            $jvCheck = 'checked';
            $jvActive = 'active';
            $varsityCheck = '';
            $varsityActive = '';
        } elseif ($tournament->level == 'Junior High') {
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
            'submitButtonText' => $submitButtonText,
        ]);
    }

    public function showTournaments()
    {
        $tournaments = Tournament::all()->sortByDesc('date');
        $schoolAttendees = SchoolAttendee::all();

        if(Auth::check()) {
            $invitations = $schoolAttendees->where('school_id', '=', Auth::user()->school_id);
            $pendingInvitations = $invitations->where('invite_status', '=', 'pending');
            $acceptedInvitations = $invitations->where('invite_status', '=', 'accepted');

            foreach ($pendingInvitations as $invitation) {
                foreach ($tournaments as $key => $tournament) {
                    if ($tournament->id == $invitation->tournament_id) {
                        $tournament->pendingInvite = true;
                        $moveTournament = $tournament;
                        $tournaments->forget($key);
                        $tournaments->prepend($moveTournament);
                    }
                }
            }
            foreach ($acceptedInvitations as $invitation) {
                foreach ($tournaments as $key => $tournament) {
                    if ($tournament->id == $invitation->tournament_id) {
                        $tournament->acceptedInvite = true;
                        $moveTournament = $tournament;
                        $tournaments->forget($key);
                        $tournaments->prepend($moveTournament);
                    }
                }
            }
        }

        foreach($tournaments as $tournament) {
            $tournament->completed = false;

            $tournamentDate = strtotime(date('Y-m-d', strtotime($tournament->date)));
            $currentDate = strtotime(date('Y-m-d'));

            if($tournamentDate < $currentDate) {
                $tournament->completed = true;
            }
            $tournament->date = date("m-d-Y", strtotime($tournament->date));
        }


        return view('tournaments', [
            'tournaments' => $tournaments,
            'schoolAttendees' => $schoolAttendees,
        ]);
    }

    public function showTournament(Tournament $tournament)
    {
        $user = Auth::user();
        $hostUser = false;
        $userHasPendingTournamentInvite = false;
        $userInviteStatus = false;

        if($user != null) {
            if($tournament->host_id === $user->school_id) {
                $hostUser = true;
            }
            $schoolAttendee = SchoolAttendee::all()->where('tournament_id', '=', $tournament->id)->where('school_id', '=', $user->school_id)->first();
            if($schoolAttendee != null) {
                $userHasPendingTournamentInvite = true;
                $userInviteStatus = $schoolAttendee->invite_status;
            }
        }


        $school = School::find($tournament->host_id);
        $schools = School::all();
//        $bracketPositions = BracketPosition::all()->where('tournament_id' ,'=', $tournament->id)->first();

        $allAttendees = SchoolAttendee::all()->where('tournament_id', '=', $tournament->id);
        foreach ($allAttendees as $attendee) {
            $attendeeSchool = $schools->where('id', '=', $attendee->school_id)->first();
            $attendee->school_name = $attendeeSchool->name;
            $attendee->conference = $attendeeSchool->conference;
            $attendeeSchoolIDs[] = $attendee->school_id;
            foreach($schools as $key => $school) {
                if($attendee->school_id === $school->id) {
                    unset($schools[$key]);
                }
            }
        }


        $acceptedAttendees = $allAttendees->where('tournament_id', '=', $tournament->id)->where('invite_status', '=', 'accepted');
        $pendingAttendees = $allAttendees->where('tournament_id', '=', $tournament->id)->where('invite_status', '=', 'pending');
        $declinedAttendees = $allAttendees->where('tournament_id', '=', $tournament->id)->where('invite_status', '=', 'declined');
        $oneSinglesPlayers = Player::all()->where('position', '=', 1);

        $attendeeSchoolIDs = [];

        $girlsOneSinglesPlayers = $oneSinglesPlayers
            ->where('gender', '=', 'Female')
            ->whereIn('school_id', $attendeeSchoolIDs)
            ->sortBy('girls_one_singles_rank');



        return view('tournament', [
            'tournament' => $tournament,
            'school' => $school,
            'allAttendees' => $allAttendees,
            'pendingAttendees' => $pendingAttendees,
            'declinedAttendees' => $declinedAttendees,
            'acceptedAttendees' => $acceptedAttendees,
            'schools' => $schools,
            'girlsOneSinglesPlayers' => $girlsOneSinglesPlayers,
            'hostUser' => $hostUser,
            'userHasPendingTournamentInvite' => $userHasPendingTournamentInvite,
            'userInviteStatus' => $userInviteStatus
        ]);
    }

    public function edit(Request $request)
    {
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


    public function create()
    {
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

        $tournament['host_id'] = Auth::user()->school_id;

        $tournament->saveOrFail();

        $schoolAttendee = new SchoolAttendee;
        $schoolAttendee->school_id = $tournament['host_id'];
        $schoolAttendee->tournament_id = $tournament->id;
        $schoolAttendee->invite_status = 'accepted';
        $schoolAttendee->saveOrFail();

        $brackets = ['girlsOneSingles', 'girlsTwoSingles', 'girlsOneDoubles', 'girlsTwoDoubles', 'boysOneSingles', 'boysTwoSingles', 'boysOneDoubles', 'boysTwoDoubles'];
        foreach($brackets as $bracket) {
            $bracketPosition = new BracketPosition;
            $bracketPosition->{'tournament_id'} = $tournament->id;
            $bracketPosition->{'bracket'} = $bracket;

            $bracketPositions = $bracketPosition->getFillable();
            array_shift($bracketPositions);
            array_shift($bracketPositions);

//            foreach($bracketPositions as $position) {
//                $bracketPosition->$position = 0;
//            }

            $bracketPosition->saveOrFail();

        }

        return redirect()->route('tournament', ['tournament' => $tournament->id]);
    }
}
