<?php

namespace App\Http\Controllers;

use App\Player;
use App\School;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PlayerController;
use App\SchoolAttendee;
use App\SinglesMatch;

class AjaxController extends Controller {
    public function savePlayerPositions(Request $request) {
        $input = $request->all();
        $updatedPositionOrder = $input['updatedPositionOrder'];
        $playerController = new PlayerController;
        $newPosition = 1;

        foreach($updatedPositionOrder as $player) {
            $playerID = intval($player[1]);
            $playerController->savePositionChanges($playerID, $newPosition);
            $newPosition++;
        }

        return response()->json(['success'=>'Saved position changes.']);

    }

    public function inviteSchools(Request $request) {
        $input = $request->all();
        $schoolInviteeIDs = $input['schoolInviteeIDs'];
        $tournamentID = intval($input['tournament_id']);

        foreach($schoolInviteeIDs as $inviteeID) {
            $inviteeID = intval($inviteeID);
            $schoolAttendee = new SchoolAttendee;
            $schoolAttendee->school_id = $inviteeID;
            $schoolAttendee->tournament_id = $tournamentID;
            $schoolAttendee->saveOrFail();
        }

        return response()->json(['success'=>'Invites sent.']);
    }

    public function saveScore(Request $request) {
        $input = $request->all();

        $winner = intval($input['winner']);
        $loser = intval($input['loser']);
        $winnerBracketPosition = $input['winnerBracketPosition'];
        $loserBracketPosition = $input['loserBracketPosition'];
        $score = $input['score'];
        $tournamentID = intval($input['tournament_id']);
        $scoreInput = $input['scoreInput'];

        $match = SinglesMatch::all()
            ->where('tournament_id', '=', $tournamentID)
            ->where('score_input', '=', $scoreInput)
            ->first();

        if($match == null) {
            $match = new SinglesMatch;
        }

        $match->winner = $winner;
        $match->loser = $loser;
        $match->winner_bracket_position = $winnerBracketPosition;
        $match->loser_bracket_position = $loserBracketPosition;
        $match->tournament_id = $tournamentID;
        $match->score = $score;
        $match->score_input = $scoreInput;

        $match->saveOrFail();

        return response()->json(['success'=>'Saved Score.']);
    }

    public function getBracketData(Request $request) {
        $request = $request->all();
        $tournament_id = $request['tournament_id'];
        $attendees = SchoolAttendee::all()->where('tournament_id', '=', $tournament_id);

        $requestedBracket = $request['requestedBracket'];
        switch ($requestedBracket) {
            case "boysOneSingles":
                $gender = 'Male';
                $position = 1;
                break;
            case "boysTwoSingles":
                $gender = 'Male';
                $position = 2;
                break;
            case "boysOneDoubles":
                $gender = 'Male';
                break;
            case "boysTwoDoubles":
                $gender = 'Male';
                break;
            case "girlsOneSingles":
                $gender = 'Female';
                $position = 1;
                break;
            case "girlsTwoSingles":
                $gender = 'Female';
                $position = 2;
                break;
            case "girlsOneDoubles":
                $gender = 'Female';
                break;
            case "girlsTwoDoubles":
                $gender = 'Female';
                break;
        }


        $oneSinglesPlayers = Player::all()->where('position', '=', $position);


        $attendeeSchoolIDs = [];
        foreach($attendees as $attendee) {
            $attendeeSchoolIDs[] = $attendee->school_id;
        }

        $girlsOneSinglesPlayers = $oneSinglesPlayers
            ->where('gender', '=', $gender)
            ->whereIn('school_id', $attendeeSchoolIDs)
            ->sortBy('one_singles_rank');

        foreach($girlsOneSinglesPlayers as $player) {
            $player->school_name = $player->getSchool()->name;
        }

        $seeds = [];
        $increment = 1;
        foreach($attendees as $attendee) {
            $seeds[$increment] = $girlsOneSinglesPlayers->where('one_singles_rank', '=', $increment)->first();
            $increment++;
        }

        return response()->json([
            'players' => $girlsOneSinglesPlayers,
            'seeds' => $seeds
        ]);
    }


}
