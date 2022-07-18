<?php

namespace App\Http\Controllers;

use App\BracketPosition;
use App\DoublesMatch;
use App\DoublesTeam;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PlayerController;
use App\Player;
use App\School;
use App\SchoolAttendee;
use App\SinglesMatch;
use App\Tournament;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function savePlayerPositions(Request $request)
    {
        $input = $request->all();
        $updatedPositionOrder = $input['updatedPositionOrder'];
        $playerController = new PlayerController;
        $newPosition = 1;
        $previousPlayerID = 0;
        foreach ($updatedPositionOrder as $player) {
            $playerID = intval($player[1]);
            $playerController->savePositionChanges($playerID, $newPosition);
            $newPosition++;

            if ($newPosition === 3) {
                $previousPlayerID = $playerID;
            }
            if ($newPosition === 4) {
                $newDoublesTeam = new DoublesTeam;
                $newDoublesTeam->player_1 = $previousPlayerID;
                $newDoublesTeam->player_2 = $playerID;
                $newDoublesTeam->saveOrFail();
            }
            if ($newPosition === 5) {
                $previousPlayerID = $playerID;
            }
            if ($newPosition === 6) {
                $newDoublesTeam = new DoublesTeam;
                $newDoublesTeam->player_1 = $previousPlayerID;
                $newDoublesTeam->player_2 = $playerID;
                $newDoublesTeam->saveOrFail();
            }
        }

        return response()->json(['success'=>'Saved position changes.']);
    }

    public function declineInvite(Request $request) {
        $input = $request->all();
        $tournamentID = intval($input['tournament_id']);
        $userSchoolID = intval($input['user_school_id']);

        $schoolAttendee = SchoolAttendee::all()->where('tournament_id', '=', $tournamentID)->where('school_id', '=', $userSchoolID)->first();
        $schoolAttendee->invite_status = 'declined';
        $schoolAttendee->saveOrFail();
        return response()->json(['redirect_url'=> url('tournaments')]);
    }

    public function acceptInvite(Request $request) {
        $input = $request->all();
        $tournamentID = intval($input['tournament_id']);
        $userSchoolID = Auth::user()->school_id;

        $schoolAttendee = SchoolAttendee::all()->where('tournament_id', '=', $tournamentID)->where('school_id', '=', $userSchoolID)->first();
        $schoolAttendee->invite_status = 'accepted';
        $schoolAttendee->saveOrFail();

        return response()->json(['redirect_url'=> url('tournaments')]);
    }

    public function removeSeededPlayer(Request $request) {
        $input = $request->all();
        $tournament_id = intval($input['tournament_id']);
        $bracket = $input['bracket'];
        $player_id = $input['player_id'];
        $seed = $input['seed'];

        $bracketPosition = BracketPosition::all()->where('tournament_id', '=', $tournament_id)->where('bracket', '=', $bracket)->first();
        $bracketPosition->$seed = 0;

        $bracketPosition->saveOrFail();

        return response()->json(['success'=> 'removed player']);
    }



    public function getPlayerDetails(Request $request) {
        $input = $request->all();
        $playerID = intval($input['playerID']);
        $player = Player::all()->where('id', '=', $playerID)->first();
        return response()->json([
            'player' => $player
        ]);
    }

    public function getRosterForTournament(Request $request) {
        $input = $request->all();
        $schoolID = intval($input['school_id']);
        $gender = $input['gender'];
        $tournament_id = $input['tournament_id'];

        $brackets = ['girlsOneSingles', 'girlsTwoSingles', 'girlsOneDoubles', 'girlsTwoDoubles', 'boysOneSingles', 'boysTwoSingles', 'boysOneDoubles', 'boysTwoDoubles'];
        $team_count = Tournament::find($tournament_id)->team_count;

        $bracketPositions = BracketPosition::all()->where('tournament_id', '=', $tournament_id);
        $players = Player::all();

        $schoolPlayers = $players->where('school_id', '=', $schoolID)->where('gender', '=', $gender)->sortBy('position');

        foreach($brackets as $bracket) {
            $bracketPosition = $bracketPositions->where('bracket','=', $bracket)->first();
            $playerIDs = [];

            for($increment = 1; $increment < $team_count + 1; $increment++) {
                $seed = $increment . '_seed';
                $playerID = $bracketPosition->$seed;
                $playerIDs[] = $playerID;
            }

            foreach($schoolPlayers as $player) {
                $player->$bracket = false;

                foreach($playerIDs as $ID) {
                    if($player->id === $ID) {
                        $player->$bracket = true;
                        continue 2;
                    }
                }
            }
        }

        return response()->json([
           'schoolPlayers' => $schoolPlayers
        ]);
    }

    public function saveTournamentSeeds(Request $request) {
        $input = $request->all();
        $playerSeeds = $input['playerSeeds'];
        $tournament_id = $input['tournament_id'];
        $bracket = $input['bracket'];
        $bracketPosition = BracketPosition::all()->where('tournament_id', '=', $tournament_id)->where('bracket', '=', $bracket)->first();

        $increment = 1;
        foreach($playerSeeds as $seededPlayerID) {
            $playerID = intval($seededPlayerID);
            $seed = $increment . '_seed';

            $bracketPosition->$seed = $playerID;
            $increment++;
        }
        $bracketPosition->saveOrFail();

        return response()->json(['success'=>'Saved Seeds']);
    }

    public function inviteSchools(Request $request)
    {
        $input = $request->all();
        $inviteeSchoolIDs = $input['inviteeSchoolIDs'];
        $inviteStatuses = $input['inviteStatuses'];
        $tournament_id = intval($input['tournament_id']);

        $attendees = SchoolAttendee::all()->where('tournament_id', '=', $tournament_id);
        $updatedSchoolIDs = [];

        foreach($inviteeSchoolIDs as $key => $ID) {
            $school_id = intval($ID);
            $invite_status = $inviteStatuses[$key];
            if($invite_status === 'not sent') {
                $invite_status = 'pending';
            }

            $attendee = $attendees->where('school_id', '=', $school_id)->first();
            if($attendee === null) {
                $attendee = new SchoolAttendee;
                $attendee->school_id = $school_id;
                $attendee->tournament_id = $tournament_id;
            }
            $attendee->invite_status = $invite_status;
            $attendee->saveOrFail();
            $updatedSchoolIDs[] = $school_id;
        }

        //deletes any removed rows from the Invite Teams Table
        foreach($attendees as $attendee) {
            if(in_array(intval($attendee->school_id), $updatedSchoolIDs)) {
                continue;
            } else {
                $attendee->delete();
            }
        }

        return response()->json(['success'=>'Invites sent.']);
    }

    public function saveScore(Request $request) {
        $scoreData = $request->all();
        $scoreInput = $scoreData['scoreInput'];
        $tournamentID = intval($scoreData['tournament_id']);
        $bracket = $scoreData['bracket'];

        $singles = false;

        if (strpos($bracket, 'Singles') !== false) {
            $singles = true;
        }

        if ($singles) {
            $match = SinglesMatch::all()
                ->where('tournament_id', '=', $tournamentID)
                ->where('score_input', '=', $scoreInput)
                ->where('bracket', '=', $bracket)
                ->first();
        } else {
            $match = DoublesMatch::all()
                ->where('tournament_id', '=', $tournamentID)
                ->where('score_input', '=', $scoreInput)
                ->where('bracket', '=', $bracket)
                ->first();
        }

        $score = $scoreData['score'];
        $match->score = $score;
        $match->saveOrFail();

        return response()->json(['success'=>'Saved Score.']);
    }

    public function saveMatch(Request $request)
    {
        $scoreData = $request->all();

        $tournamentID = intval($scoreData['tournament_id']);
        $bracket = $scoreData['bracket'];
        $bracketPositions = BracketPosition::all()->where('tournament_id', '=', $tournamentID)->where('bracket', '=', $bracket)->first();

        //finds the new bracket position for the winner and loser
        $scoreData['newWinnerBracketPosition'] = $bracketPositions->winningPathAssociations[$scoreData['winnerBracketPosition']];
        $scoreData['newLoserBracketPosition'] = $bracketPositions->losingPathAssociations[$scoreData['loserBracketPosition']];

        $singles = false;

        if (strpos($bracket, 'Singles') !== false) {
            $singles = true;
        }

        $score = $scoreData['score'];
        $scoreInput = $bracketPositions->winningPathAssociations[$scoreData['winnerBracketPosition']] . '-score-input';

        if ($singles) {
            $match = SinglesMatch::all()
                ->where('tournament_id', '=', $tournamentID)
                ->where('score_input', '=', $scoreInput)
                ->where('bracket', '=', $bracket)
                ->first();
            if ($match == null) {
                $match = new SinglesMatch;
            }
        } else {
            $match = DoublesMatch::all()
                ->where('tournament_id', '=', $tournamentID)
                ->where('score_input', '=', $scoreInput)
                ->where('bracket', '=', $bracket)
                ->first();
            if ($match == null) {
                $match = new DoublesMatch;
            }
        }

        $winner = intval($scoreData['winner']);
        $loser = intval($scoreData['loser']);
        $winnerBracketPosition = $scoreData['winnerBracketPosition'];
        $loserBracketPosition = $scoreData['loserBracketPosition'];
        $newWinnerBracketPosition = str_replace('-', '_', $scoreData['newWinnerBracketPosition']);

        $bracketPositions->$newWinnerBracketPosition = $winner;
        if (isset($scoreData['newLoserBracketPosition'])) {
            $newLoserBracketPosition = str_replace('-', '_', $scoreData['newLoserBracketPosition']);
            $bracketPositions->$newLoserBracketPosition = $loser;
        }
        $bracketPositions->saveOrFail();

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


    public function getBracketData(Request $request)
    {
        $request = $request->all();
        $tournament_id = $request['tournament_id'];
        $attendees = SchoolAttendee::all()->where('tournament_id', '=', $tournament_id);
        $singles = false;

        $requestedBracket = $request['requestedBracket'];
        switch ($requestedBracket) {
            case 'boysOneSingles':
                $singles = true;
                $gender = 'Male';
                $position = [1];
                $sort = 'boys_one_singles_rank';
                break;
            case 'boysTwoSingles':
                $singles = true;
                $gender = 'Male';
                $position = [2];
                $sort = 'boys_two_singles_rank';
                break;
            case 'boysOneDoubles':
                $gender = 'Male';
                $position = [3, 4];
                $sort = 'boys_one_doubles_rank';
                break;
            case 'boysTwoDoubles':
                $gender = 'Male';
                $position = [5, 6];
                $sort = 'boys_two_doubles_rank';
                break;
            case 'girlsOneSingles':
                $singles = true;
                $gender = 'Female';
                $position = [1];
                $sort = 'girls_one_singles_rank';
                break;
            case 'girlsTwoSingles':
                $singles = true;
                $gender = 'Female';
                $position = [2];
                $sort = 'girls_two_singles_rank';
                break;
            case 'girlsOneDoubles':
                $gender = 'Female';
                $position = [3, 4];
                $sort = 'girls_one_doubles_rank';
                break;
            case 'girlsTwoDoubles':
                $gender = 'Female';
                $position = [5, 6];
                $sort = 'girls_two_doubles_rank';
                break;
        }

        $attendeeSchoolIDs = [];
        foreach ($attendees as $attendee) {
            $attendeeSchoolIDs[] = $attendee->school_id;
        }

        $bracketPositions = BracketPosition::all()->where('tournament_id', '=', $tournament_id)->where('bracket', $requestedBracket)->first();
        $bracketPositionClass = new BracketPosition;
        $bracketPositionTitles = $bracketPositionClass->getFillable();
        array_shift($bracketPositionTitles);
        array_shift($bracketPositionTitles);

        if ($singles) {
            $singlesPlayers = Player::all()
                ->where('gender', '=', $gender)
                ->whereIn('school_id', $attendeeSchoolIDs)
                ->whereIn('position', $position)
                ->sortBy($sort);

            foreach ($singlesPlayers as $player) {
                $player->school_name = $player->getSchool()->name;
            }

            $existingBracketPosition = true;
            if ($bracketPositions == null) {
                $existingBracketPosition = false;
                $bracketPositions = new BracketPosition();
                $bracketPositions->tournament_id = $tournament_id;
                $bracketPositions->bracket = $requestedBracket;
                $increment = 1;
                foreach ($singlesPlayers as $player) {
                    $seed = $increment.'_seed';
                    $bracketPositions->$seed = $player->id;
                    $increment++;
                }
                $bracketPositions->saveOrFail();
            }

            for ($increment = 1; $increment < (count($singlesPlayers) + 1); $increment++) {
                foreach ($singlesPlayers as $player) {
                    if ($player->id == $bracketPositions->{$increment.'_seed'}) {
                        $bracketPositions->{$increment.'_seed'} = $player->first_name.' '.$player->last_name;
                        $bracketPositions->{$increment.'_seed_id'} = $player->id;
                        $bracketPositions->{$increment.'_seed_school'} = $player->getSchool()->name;
                        $bracketPositions->{$increment.'_seed_conference'} = $player->getSchool()->conference;
                        break;
                    }
                }
            }

            if ($existingBracketPosition) {
                foreach ($bracketPositionTitles as $key => $title) {
                    if ($bracketPositions->$title != 0) {
                        $player = $singlesPlayers->find($bracketPositions->$title);
                        $playerName = $player->first_name.' '.$player->last_name;
                        $bracketPositions->{$title} = $playerName;
                        $bracketPositions->{$title.'_id'} = $player->id;
                    }
                }
            }

            $matches = SinglesMatch::all()->where('tournament_id', '=', $tournament_id);

            return response()->json([
                'players' => $singlesPlayers,
                'bracketPositions' => $bracketPositions,
                'matches' => $matches,
            ]);
        } else {
            foreach ($attendees as $attendee) {
                $doublesTeams[$attendee->school_id] = $attendee->getSchool()->getGirlsOneDoublesTeam();
            }

            $tournament = Tournament::find($tournament_id);
            $doublesTeams = $tournament->getGirlsDoublesSortedByRank($requestedBracket);

            if ($bracketPositions == null) {
                $bracketPositions = new BracketPosition();
                $bracketPositions->tournament_id = $tournament_id;
                $bracketPositions->bracket = $requestedBracket;
                $increment = 1;
                foreach ($doublesTeams as $team) {
                    $seed = $increment.'_seed';
                    $bracketPositions->$seed = $team['id'];
                    $increment++;
                }
                $bracketPositions->saveOrFail();
            }

            for ($increment = 1; $increment < (count($doublesTeams) + 1); $increment++) {
                foreach ($doublesTeams as $team) {
                    $playerOne = $team[0];
                    $playerTwo = $team[1];
                    if ($team['id'] === $bracketPositions->{$increment.'_seed'}) {
                        $bracketPositions->{$increment.'_seed'} = $playerOne->last_name.' / '.$playerTwo->last_name;
                        $bracketPositions->{$increment.'_seed_id'} = $team['id'];
                        $bracketPositions->{$increment.'_seed_school'} = $playerOne->getSchool()->name;
                        $bracketPositions->{$increment.'_seed_conference'} = $playerOne->getSchool()->conference;
                        break;
                    }
                }
            }

            foreach ($bracketPositionTitles as $key => $title) {
                if ($key != 0 && $bracketPositions->$title != 0) {
                    $team = $doublesTeams[$bracketPositions->$title];
                    $playerOne = $team[0];
                    $playerTwo = $team[1];
                    $playersNamesCombined = $playerOne->last_name.' / '.$playerTwo->last_name;
                    $bracketPositions->{$title} = $playersNamesCombined;
                    $bracketPositions->{$title.'_id'} = $team['id'];
                }
            }

            $matches = DoublesMatch::all()->where('tournament_id', '=', $tournament_id);

            return response()->json([
                'doublesTeams' => $doublesTeams,
                'bracketPositions' => $bracketPositions,
                'matches' => $matches,
            ]);
        }
    }
}
