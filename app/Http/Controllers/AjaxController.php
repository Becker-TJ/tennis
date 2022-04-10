<?php

namespace App\Http\Controllers;

use App\Player;
use App\School;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PlayerController;
use App\SchoolAttendee;
use App\SinglesMatch;
use App\BracketPosition;

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

        $tournamentID = intval($input['tournament_id']);
        $bracketPositions = BracketPosition::all()->where('tournament_id' ,'=', $tournamentID)->first();

        $winner = intval($input['winner']);
        $loser = intval($input['loser']);
        $winnerBracketPosition = $input['winnerBracketPosition'];
        $loserBracketPosition = $input['loserBracketPosition'];
        $newWinnerBracketPosition = str_replace('-', '_', $input['newWinnerBracketPosition']);

        $bracketPositions->$newWinnerBracketPosition = $winner;
        if(isset($input['newLoserBracketPosition'])) {
            $newLoserBracketPosition = str_replace('-', '_', $input['newLoserBracketPosition']);
            $bracketPositions->$newLoserBracketPosition = $loser;
        }

        $score = $input['score'];
        $scoreInput = $input['scoreInput'];

        $bracketPositions->saveOrFail();

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
        $bracketPositions = BracketPosition::all()->where('tournament_id' ,'=', $tournament_id)->first();

        $requestedBracket = $request['requestedBracket'];
        switch ($requestedBracket) {
            case "boysOneSingles":
                $gender = 'Male';
                $position = 1;
                $sort = 'boys_one_singles_rank';
                break;
            case "boysTwoSingles":
                $gender = 'Male';
                $position = 2;
                $sort = 'boys_two_singles_rank';
                break;
            case "boysOneDoubles":
                $gender = 'Male';
                $sort = 'boys_one_doubles_rank';
                break;
            case "boysTwoDoubles":
                $gender = 'Male';
                $sort = 'boys_two_doubles_rank';
                break;
            case "girlsOneSingles":
                $gender = 'Female';
                $position = 1;
                $sort = 'girls_one_singles_rank';
                break;
            case "girlsTwoSingles":
                $gender = 'Female';
                $position = 2;
                $sort = 'girls_two_singles_rank';
                break;
            case "girlsOneDoubles":
                $gender = 'Female';
                $sort = 'girls_one_doubles_rank';
                break;
            case "girlsTwoDoubles":
                $gender = 'Female';
                $sort = 'girls_two_doubles_rank';
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
            ->sortBy($sort);

        foreach($girlsOneSinglesPlayers as $player) {
            $player->school_name = $player->getSchool()->name;
        }

//        $seeds = [];
//        $increment = 1;
//        foreach($attendees as $attendee) {
//            $seeds[$increment] = $girlsOneSinglesPlayers->where($sort, '=', $increment)->first();
//            $increment++;
//        }

        if($bracketPositions == null) {
            $bracketPositions = new BracketPosition();
            $bracketPositions->tournament_id = $tournament_id;
            $increment = 1;
            foreach($girlsOneSinglesPlayers as $player) {
                $seed = $increment . '_seed';
                $bracketPositions->$seed = $player->id;
                $increment++;
            }
            $bracketPositions->saveOrFail();
        } else {
            $bracketPositions = BracketPosition::all()->where('tournament_id', '=', $tournament_id)->first();
        }
        for ($increment = 1; $increment < (count($girlsOneSinglesPlayers) + 1); $increment++) {
            foreach($girlsOneSinglesPlayers as $player) {
                if($player->id == $bracketPositions->{$increment . '_seed'}) {
                    $bracketPositions->{$increment . '_seed'} = $player->first_name . ' ' . $player->last_name;
                    $bracketPositions->{$increment . '_seed_id'} = $player->id;
                    $bracketPositions->{$increment . '_seed_school'} = $player->getSchool()->name;
                    break;
                }
            }
        }
        $bracketPositionTitles = $bracketPositions->getFillable();
        array_shift($bracketPositionTitles);

        foreach($bracketPositionTitles as $key => $title) {
            if($bracketPositions->$title != 0) {
                $player = $girlsOneSinglesPlayers->find($bracketPositions->$title);
                $playerName = $player->first_name . ' ' . $player->last_name;
                $bracketPositions->{$title} = $playerName;
                $bracketPositions->{$title . '_id'} = $player->id;
            }
        }

        $matches = SinglesMatch::all()->where('tournament_id', '=', $tournament_id);

        return response()->json([
            'players' => $girlsOneSinglesPlayers,
            'bracketPositions' => $bracketPositions,
            'matches' => $matches
        ]);
    }


}
