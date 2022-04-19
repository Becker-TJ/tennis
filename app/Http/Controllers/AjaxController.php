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
use App\DoublesTeam;
use App\Tournament;
use App\DoublesMatch;

class AjaxController extends Controller {
    public function savePlayerPositions(Request $request) {
        $input = $request->all();
        $updatedPositionOrder = $input['updatedPositionOrder'];
        $playerController = new PlayerController;
        $newPosition = 1;
        $previousPlayerID = 0;
        foreach($updatedPositionOrder as $player) {
            $playerID = intval($player[1]);
            $playerController->savePositionChanges($playerID, $newPosition);
            $newPosition++;

            if($newPosition === 3) {
                $previousPlayerID = $playerID;
            }
            if($newPosition === 4) {
                $newDoublesTeam = new DoublesTeam;
                $newDoublesTeam->player_1 = $previousPlayerID;
                $newDoublesTeam->player_2 = $playerID;
                $newDoublesTeam->saveOrFail();
            }
            if($newPosition === 5) {
                $previousPlayerID = $playerID;
            }
            if($newPosition === 6) {
                $newDoublesTeam = new DoublesTeam;
                $newDoublesTeam->player_1 = $previousPlayerID;
                $newDoublesTeam->player_2 = $playerID;
                $newDoublesTeam->saveOrFail();
            }
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
        $bracketPositions = BracketPosition::all()->where('tournament_id' ,'=', $tournamentID)->where('bracket', '=', 'bracket')->first();

        $singles = false;
        $bracket = $input['bracket'];
        if(strpos($bracket, 'Singles') !== false) {
            $singles = true;
        }
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

        if($singles) {
            $match = SinglesMatch::all()
                ->where('tournament_id', '=', $tournamentID)
                ->where('score_input', '=', $scoreInput)
                ->where('bracket', '=', $bracket)
                ->first();
            if($match == null) {
                $match = new SinglesMatch;
            }
        } else {
            $match = DoublesMatch::all()
                ->where('tournament_id', '=', $tournamentID)
                ->where('score_input', '=', $scoreInput)
                ->where('bracket', '=', $bracket)
                ->first();
            if($match == null) {
                $match = new DoublesMatch;
            }
        }

        $match->bracket = $bracket;
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
        $singles = false;
        $doubles = false;

        $requestedBracket = $request['requestedBracket'];
        switch ($requestedBracket) {
            case "boysOneSingles":
                $singles = true;
                $gender = 'Male';
                $position = [1];
                $sort = 'boys_one_singles_rank';
                break;
            case "boysTwoSingles":
                $singles = true;
                $gender = 'Male';
                $position = [2];
                $sort = 'boys_two_singles_rank';
                break;
            case "boysOneDoubles":
                $gender = 'Male';
                $position = [3,4];
                $sort = 'boys_one_doubles_rank';
                break;
            case "boysTwoDoubles":
                $gender = 'Male';
                $position = [5,6];
                $sort = 'boys_two_doubles_rank';
                break;
            case "girlsOneSingles":
                $singles = true;
                $gender = 'Female';
                $position = [1];
                $sort = 'girls_one_singles_rank';
                break;
            case "girlsTwoSingles":
                $singles = true;
                $gender = 'Female';
                $position = [2];
                $sort = 'girls_two_singles_rank';
                break;
            case "girlsOneDoubles":
                $gender = 'Female';
                $position = [3,4];
                $sort = 'girls_one_doubles_rank';
                break;
            case "girlsTwoDoubles":
                $gender = 'Female';
                $position = [5,6];
                $sort = 'girls_two_doubles_rank';
                break;
        }

        $attendeeSchoolIDs = [];
        foreach($attendees as $attendee) {
            $attendeeSchoolIDs[] = $attendee->school_id;
        }

        $bracketPositions = BracketPosition::all()->where('tournament_id' ,'=', $tournament_id)->where('bracket', $requestedBracket)->first();
        $bracketPositionClass = new BracketPosition;
        $bracketPositionTitles = $bracketPositionClass->getFillable();
        array_shift($bracketPositionTitles);
        array_shift($bracketPositionTitles);


        if($singles) {
            $singlesPlayers = Player::all()
                ->where('gender', '=', $gender)
                ->whereIn('school_id', $attendeeSchoolIDs)
                ->whereIn('position', $position)
                ->sortBy($sort);

            foreach($singlesPlayers as $player) {
                $player->school_name = $player->getSchool()->name;
            }

            $existingBracketPosition = true;
            if($bracketPositions == null) {
                $existingBracketPosition = false;
                $bracketPositions = new BracketPosition();
                $bracketPositions->tournament_id = $tournament_id;
                $bracketPositions->bracket = $requestedBracket;
                $increment = 1;
                foreach($singlesPlayers as $player) {
                    $seed = $increment . '_seed';
                    $bracketPositions->$seed = $player->id;
                    $increment++;
                }
                $bracketPositions->saveOrFail();

            }

            for ($increment = 1; $increment < (count($singlesPlayers) + 1); $increment++) {
                foreach($singlesPlayers as $player) {
                    if($player->id == $bracketPositions->{$increment . '_seed'}) {
                        $bracketPositions->{$increment . '_seed'} = $player->first_name . ' ' . $player->last_name;
                        $bracketPositions->{$increment . '_seed_id'} = $player->id;
                        $bracketPositions->{$increment . '_seed_school'} = $player->getSchool()->name;
                        break;
                    }
                }
            }

            if($existingBracketPosition) {
                foreach($bracketPositionTitles as $key => $title) {
                    if($bracketPositions->$title != 0) {
                        $player = $singlesPlayers->find($bracketPositions->$title);
                        $playerName = $player->first_name . ' ' . $player->last_name;
                        $bracketPositions->{$title} = $playerName;
                        $bracketPositions->{$title . '_id'} = $player->id;
                    }
                }
            }


            $matches = SinglesMatch::all()->where('tournament_id', '=', $tournament_id);

            return response()->json([
                'players' => $singlesPlayers,
                'bracketPositions' => $bracketPositions,
                'matches' => $matches
            ]);

        } else {

            foreach($attendees as $attendee) {
                $doublesTeams[$attendee->school_id] = $attendee->getSchool()->getGirlsOneDoublesTeam();
            }

            $tournament = Tournament::find($tournament_id);
            $doublesTeams = $tournament->getGirlsOneDoublesSortedByRank();

            if($bracketPositions == null) {
                $bracketPositions = new BracketPosition();
                $bracketPositions->tournament_id = $tournament_id;
                $bracketPositions->bracket = $requestedBracket;
                $increment = 1;
                foreach($doublesTeams as $team) {
                    $seed = $increment . '_seed';
                    $bracketPositions->$seed = $team['id'];
                    $increment++;
                }
                $bracketPositions->saveOrFail();
            }

            for ($increment = 1; $increment < (count($doublesTeams) + 1); $increment++) {
                foreach($doublesTeams as $team) {
                    $playerOne = $team[0];
                    $playerTwo = $team[1];
                    if($team['id'] === $bracketPositions->{$increment . '_seed'}) {
                        $bracketPositions->{$increment . '_seed'} = $playerOne->last_name . ' / ' . $playerTwo->last_name;
                        $bracketPositions->{$increment . '_seed_id'} = $team['id'];
                        $bracketPositions->{$increment . '_seed_school'} = $playerOne->getSchool()->name;
                        break;
                    }
                }
            }

            foreach($bracketPositionTitles as $key => $title) {
                if($key != 0 && $bracketPositions->$title != 0) {
                    $team = $doublesTeams[$bracketPositions->$title];
                    $playerOne = $team[0];
                    $playerTwo = $team[1];
                    $playersNamesCombined = $playerOne->last_name . ' / ' . $playerTwo->last_name;
                    $bracketPositions->{$title} = $playersNamesCombined;
                    $bracketPositions->{$title . '_id'} = $team['id'];
                }
            }

            $matches = DoublesMatch::all()->where('tournament_id', '=', $tournament_id);

            return response()->json([
                'doublesTeams' => $doublesTeams,
                'bracketPositions' => $bracketPositions,
                'matches' => $matches
            ]);
        }




    }


}
