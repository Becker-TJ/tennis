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

        $tournament = Tournament::find($tournamentID);
        $tournament->updateBracketPositionsWithAllAttendees();

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

    public function addNewSeededPlayer(Request $request) {
        $input = $request->all();
        $tournament_id = intval($input['tournament_id']);
        $bracket = $input['bracket'];
        $player_id = intval($input['player_id']);

        $bracketPosition = BracketPosition::all()->where('tournament_id', '=', $tournament_id)->where('bracket', '=', $bracket)->first();
        $tournament = Tournament::find($tournament_id);
        $numberOfTeams = $tournament->team_count;

        $addedPlayer = false;
        for ($x = 1; $x <= $numberOfTeams; $x++) {
            $seed = $x . '_seed';
            if ($bracketPosition->$seed === 0) {
                $bracketPosition->$seed = $player_id;
                $bracketPosition->saveOrFail();
                $addedPlayer = true;
                break;
            }
        }
        if(!$addedPlayer) {
            return response()->json(['success' => 'already full']);
        } else {
            return response()->json(['success'=> 'added player']);
        }

    }

    public function addNewSeededDoublesTeam(Request $request) {
        $input = $request->all();
        $tournament_id = intval($input['tournament_id']);
        $bracket = $input['bracket'];
        $player_one_id = intval($input['player_one_id']);
        $player_two_id = intval($input['player_two_id']);

        $doublesTeamClass = new DoublesTeam;
        $doublesTeam = $doublesTeamClass->findDoublesTeam($player_one_id, $player_two_id);

        if(!$doublesTeam) {
            $doublesTeam = new DoublesTeam;
            $doublesTeam->player_1 = $player_one_id;
            $doublesTeam->player_2 = $player_two_id;
            $doublesTeam->saveOrFail();
        }


        $bracketPosition = BracketPosition::all()->where('tournament_id', '=', $tournament_id)->where('bracket', '=', $bracket)->first();
        $tournament = Tournament::find($tournament_id);
        $numberOfTeams = $tournament->team_count;

        $addedTeam = false;
        for ($x = 1; $x <= $numberOfTeams; $x++) {
            $seed = $x . '_seed';
            if ($bracketPosition->$seed === 0) {
                $bracketPosition->$seed = $doublesTeam->id;
                $bracketPosition->saveOrFail();
                $addedTeam = true;
                break;
            }
        }
        if(!$addedTeam) {
            return response()->json(['success' => 'already full']);
        } else {
            return response()->json(['success'=> 'added team']);
        }
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
        $tournament = Tournament::find($tournament_id);
        $correctPlayerOrder = [];
        $playersNotInTournament = [];
        $doublesPlayersToUpdateInSchoolPlayersList = [];
        $foundAPlayerInPreviousBracket = true;
        $fullTournament = [
            'girlsOneSingles' => true,
            'girlsTwoSingles' => true,
            'girlsOneDoubles' => true,
            'girlsTwoDoubles' => true,
            'boysOneSingles' => true,
            'boysTwoSingles' => true,
            'boysOneDoubles' => true,
            'boysTwoDoubles' => true
        ];

        if ($gender === "Male") {
            $brackets = ['boysOneSingles', 'boysTwoSingles', 'boysOneDoubles', 'boysTwoDoubles'];
        } else {
            $brackets = ['girlsOneSingles', 'girlsTwoSingles', 'girlsOneDoubles', 'girlsTwoDoubles'];
        }
        $bracketsPrettyPrintAssociations = [
            'girlsOneSingles' => 'One Singles',
            'girlsTwoSingles' => 'Two Singles',
            'girlsOneDoubles' => 'One Doubles',
            'girlsTwoDoubles' => 'Two Doubles',
            'boysOneSingles' => 'One Singles',
            'boysTwoSingles' => 'Two Singles',
            'boysOneDoubles' => 'One Doubles',
            'boysTwoDoubles' => 'Two Doubles'
        ];

        $team_count = $tournament->team_count;

        $bracketPositions = BracketPosition::all()->where('tournament_id', '=', $tournament_id);

        if($bracketPositions === null) {
            $tournament->updateBracketPositionsWithAllAttendees();
        }

        $players = Player::all();

        $schoolPlayers = $players->where('school_id', '=', $schoolID)->where('gender', '=', $gender)->sortBy('position');


        foreach($brackets as $bracket) {
            if(!$foundAPlayerInPreviousBracket) {
                $prettyName = $bracketsPrettyPrintAssociations[$previousBracket];
                $newPlayer = new Player;
                $newPlayer->in_tournament = false;
                $newPlayer->id = 0;
                $newPlayer->first_name = "-";
                $newPlayer->last_name = "";
                $newPlayer->grade = "-";
                $newPlayer->real_player = false;
                $newPlayer->bracket_name = $prettyName;
                $correctPlayerOrder[] = $newPlayer;
                if($prettyName === "One Doubles") {
                    $newPlayer = new Player;
                    $newPlayer->in_tournament = false;
                    $newPlayer->id = 0;
                    $newPlayer->first_name = "-";
                    $newPlayer->last_name = "";
                    $newPlayer->grade = "-";
                    $newPlayer->real_player = false;
                    $newPlayer->bracket_name = $bracketsPrettyPrintAssociations[$previousBracket];
                    $correctPlayerOrder[] = $newPlayer;
                }
            }
            $foundAPlayerInPreviousBracket = false;
            if(strpos($bracket, 'Singles')) {
                $singles = true;
            } else {
                $singles = false;
            }
            $bracketPosition = $bracketPositions->where('bracket','=', $bracket)->first();
            $playerIDs = [];

            for($increment = 1; $increment < $team_count + 1; $increment++) {
                $seed = $increment . '_seed';
                $playerID = $bracketPosition->$seed;
                $playerIDs[] = $playerID;
            }

            foreach($playerIDs as $ID) {
                if($ID === 0) {
                    $fullTournament[$bracket] = false;
                    break;
                }
            }

            if($singles) {
                foreach($schoolPlayers as $player) {
                    $player->$bracket = false;

                    foreach($playerIDs as $ID) {
                        if($player->id === $ID) {
                            $foundAPlayerInPreviousBracket = true;
                            $player->$bracket = true;
                            $player->in_tournament = true;
                            $player->bracket_name = $bracketsPrettyPrintAssociations[$bracket];
                            $correctPlayerOrder[] = $player;
                        }
                    }
                }
            } else {
                foreach($playerIDs as $doublesTeamID) {
                    if($doublesTeamID === 0) {
                        continue;
                    }
                    $doublesTeam = DoublesTeam::find($doublesTeamID);
                    $doublesPlayers = $doublesTeam->getPlayerDetails();

                    foreach($doublesPlayers as $player) {
                        if($player->school_id === $schoolID) {
                            if(!in_array($player->id, $doublesPlayersToUpdateInSchoolPlayersList)) {
                                $foundAPlayerInPreviousBracket = true;
                                $doublesPlayerInfo = [];
                                $doublesPlayerInfo['bracket'] = $bracket;
                                $doublesPlayerInfo['doublesTeamID'] = $doublesTeam->id;
                                $doublesPlayersToUpdateInSchoolPlayersList[$player->id] = $doublesPlayerInfo;
                            }
                        }
                    }
                }
            }
            $previousBracket = $bracket;
        }


        foreach($schoolPlayers as $player) {
            $player->real_player = true;
            if($player->in_tournament != true) {
                if(array_key_exists($player->id, $doublesPlayersToUpdateInSchoolPlayersList)) {
                    $playerBracket = $doublesPlayersToUpdateInSchoolPlayersList[$player->id]['bracket'];
                    $player->$playerBracket = true;
                    $player->in_tournament = true;
                    $player->doubles_team_id = $doublesPlayersToUpdateInSchoolPlayersList[$player->id]['doublesTeamID'];
                    $player->bracket_name = $bracketsPrettyPrintAssociations[$playerBracket];
                    $correctPlayerOrder[] = $player;
                } else {
                    $player->bracket_name = "-";
                    $player->doubles_team_id = false;
                    $playersNotInTournament[] = $player;
                }
            }
        }

        if(!$foundAPlayerInPreviousBracket && $bracketsPrettyPrintAssociations[$previousBracket] === "Two Doubles") {
            $newPlayer = new Player;
            $newPlayer->in_tournament = false;
            $newPlayer->id = 0;
            $newPlayer->first_name = "-";
            $newPlayer->last_name = "";
            $newPlayer->grade = "-";
            $newPlayer->real_player = false;
            $newPlayer->bracket_name = $bracketsPrettyPrintAssociations[$previousBracket];
            $correctPlayerOrder[] = $newPlayer;

            $newPlayer = new Player;
            $newPlayer->in_tournament = false;
            $newPlayer->id = 0;
            $newPlayer->first_name = "-";
            $newPlayer->last_name = "";
            $newPlayer->grade = "-";
            $newPlayer->real_player = false;
            $newPlayer->bracket_name = $bracketsPrettyPrintAssociations[$previousBracket];
            $correctPlayerOrder[] = $newPlayer;
        }

        $correctPlayerOrder = array_merge($correctPlayerOrder, $playersNotInTournament);

        return response()->json([
           'schoolPlayers' => $correctPlayerOrder,
            'fullTournament' => $fullTournament
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

    public function compareCoachPasscode(Request $request) {
        $input = $request->all();
        $enteredPasscode = $input['enteredPasscode'];
        if($enteredPasscode === "Kick Serve") {
            return response()->json([
                'passcodeMatch' => true
            ]);
        } else {
            return response()->json([
                'passcodeMatch' => false
            ]);
        }
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

        return response()->json([
            'matchID' => $match->id,
            'scoreInput' => $scoreInput
        ]);
    }


    public function saveBasicMatch(Request $request) {
        $scoreData = $request->all();
        $scoreInput = $request['score_input'];

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

        $match->bracket = $bracket;
        $match->tournament_id = $tournamentID;
        $match->score_input = $scoreInput;
        $match->saveOrFail();

        return response()->json(['success'=>'Saved Match.']);
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
        if (isset($scoreData['newLoserBracketPosition']) && $scoreData['newLoserBracketPosition'] != 'skip') {
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

    public function saveCourtSelection(Request $request) {
        $request = $request->all();
        $tournamentID = intval($request['tournament_id']);
        $matchID = intval($request['matchID']);
        $bracket = $request['bracket'];
        $court = intval($request['court']);
        $scoreInput = $request['scoreInput'];
        if(strpos($bracket, 'Singles')) {
            $singles = true;
        } else {
            $singles = false;
        }

        if($singles) {
            $match = SinglesMatch::find($matchID);
            if($match === null) {
                $match = SinglesMatch::all()->where('bracket', '=', $bracket)->where('score_input', '=', $scoreInput)->first();
                if($match === null) {
                    $match = new SinglesMatch;
                    $match->score_input = $scoreInput;
                    $match->tournament_id = $tournamentID;
                    $match->bracket = $bracket;
                }
            }
        } else {
            $match = DoublesMatch::find($matchID);
            if($match === null) {
                $match = DoublesMatch::all()->where('bracket', '=', $bracket)->where('score_input', '=', $scoreInput)->first();
                if($match === null) {
                    $match = new DoublesMatch;
                    $match->score_input = $scoreInput;
                    $match->tournament_id = $tournamentID;
                    $match->bracket = $bracket;
                }
            }
        }

        $match->court = $court;
        $match->saveOrFail();

        return response()->json(['success'=>'Saved Court Assignment.']);
    }

    public function getBracketData(Request $request)
    {
        $request = $request->all();
        $tournament_id = $request['tournament_id'];
        $tournament = Tournament::find($tournament_id);
        $attendees = SchoolAttendee::all()->where('tournament_id', '=', $tournament_id);
        $singles = false;
        $allSinglesMatches = SinglesMatch::all()->where('tournament_id', '=', $tournament_id);
        $allDoublesMatches = DoublesMatch::all()->where('tournament_id', '=', $tournament_id);

        $courtsInUse = [];
        foreach($allSinglesMatches as $match) {
            if($match->court != 0) {
                $courtsInUse[] = $match->court;
            }
        }
        foreach($allDoublesMatches as $match) {
            if($match->court != 0) {
                $courtsInUse[] = $match->court;
            }
        }

        $requestedBracket = $request['requestedBracket'];
        switch ($requestedBracket) {
            case 'boysOneSingles':
                $singles = true;
                $gender = 'Male';
                $sort = 'boys_one_singles_rank';
                break;
            case 'boysTwoSingles':
                $singles = true;
                $gender = 'Male';
                $sort = 'boys_two_singles_rank';
                break;
            case 'boysOneDoubles':
                $gender = 'Male';
                $sort = 'boys_one_doubles_rank';
                break;
            case 'boysTwoDoubles':
                $gender = 'Male';
                $sort = 'boys_two_doubles_rank';
                break;
            case 'girlsOneSingles':
                $singles = true;
                $gender = 'Female';
                $sort = 'girls_one_singles_rank';
                break;
            case 'girlsTwoSingles':
                $singles = true;
                $gender = 'Female';
                $sort = 'girls_two_singles_rank';
                break;
            case 'girlsOneDoubles':
                $gender = 'Female';
                $sort = 'girls_one_doubles_rank';
                break;
            case 'girlsTwoDoubles':
                $gender = 'Female';
                $sort = 'girls_two_doubles_rank';
                break;
        }

        $attendeeSchoolIDs = [];
        foreach ($attendees as $attendee) {
            $attendeeSchoolIDs[] = $attendee->school_id;
        }

        $bracketPositions = BracketPosition::all()->where('tournament_id', '=', $tournament_id)->where('bracket', $requestedBracket)->first();

        if($bracketPositions === null) {
            $tournament->updateBracketPositionsWithAllAttendees();
        }

        $bracketPositionClass = new BracketPosition;
        $bracketPositionTitles = $bracketPositionClass->getFillable();
        array_shift($bracketPositionTitles);
        array_shift($bracketPositionTitles);

        if ($singles) {
            $singlesPlayers = Player::all()
                ->where('gender', '=', $gender)
                ->whereIn('school_id', $attendeeSchoolIDs)
                ->sortBy($sort);

            foreach ($singlesPlayers as $player) {
                $player->school_name = $player->getSchool()->name;
            }

            for ($increment = 1; $increment <= $tournament->team_count; $increment++) {
                if(is_null($bracketPositions->{$increment . '_seed'})) {
                    $bracketPositions->{$increment .'_seed'} = "-";
                    $bracketPositions->{$increment .'_seed_id'} = 0;
                    $bracketPositions->{$increment .'_seed_school'} = "-";
                    $bracketPositions->{$increment .'_seed_conference'} = "-";
                }

                if($bracketPositions->{$increment .'_seed'} === 0) {
                    $bracketPositions->{$increment .'_seed'} = "BYE";
                    $bracketPositions->{$increment .'_seed_id'} = "0";
                    $bracketPositions->{$increment .'_seed_school'} = "BYE";
                    $bracketPositions->{$increment .'_seed_conference'} = "BYE";
                }

                foreach ($singlesPlayers as $player) {
                    if ($player->id == $bracketPositions->{$increment.'_seed'}) {
                        $bracketPositions->{$increment .'_seed'} = $player->first_name.' '.$player->last_name;
                        $bracketPositions->{$increment .'_seed_id'} = $player->id;
                        $bracketPositions->{$increment .'_seed_school'} = $player->getSchool()->name;
                        $bracketPositions->{$increment .'_seed_conference'} = $player->getSchool()->conference;
                        break;
                    }
                }
            }

            foreach ($bracketPositionTitles as $key => $title) {
                if($bracketPositions->$title === 0) {
                    $bracketPositions->$title = 'BYE';
                    $bracketPositions->{$title .'_id'} = "0";
                    $bracketPositions->{$title .'_school'} = "BYE";
                    $bracketPositions->{$title .'_conference'} = "BYE";
                    continue;
                }

                if ($bracketPositions->$title != 0) {
                    $player = $singlesPlayers->find($bracketPositions->$title);
                    $playerName = $player->first_name.' '.$player->last_name;
                    $schoolName = $player->getSchool()->name;
                    $bracketPositions->{$title} = $schoolName;
                    $bracketPositions->{$title.'_id'} = $player->id;
                    $bracketPositions->{$title.'_school'} = $schoolName;
                }
            }

            $matches = $allSinglesMatches->where('bracket', '=', $requestedBracket);

            return response()->json([
                'players' => $singlesPlayers,
                'bracketPositions' => $bracketPositions,
                'matches' => $matches,
                'courtCount' => $tournament->courts,
                'courtsInUse' => $courtsInUse
            ]);

        } else {

            $doublesTeams = $tournament->getDoublesSortedByTournamentSeed($requestedBracket);

            for ($increment = 1; $increment <= (count($doublesTeams)); $increment++) {
                foreach ($doublesTeams as $team) {
                    $playerOne = $team[0];
                    $playerTwo = $team[1];
                    if ($team['id'] === $bracketPositions->{$increment.'_seed'}) {
                        if($team['id'] === 0) {
                            $bracketPositions->{$increment.'_seed'} = "BYE";
                            $bracketPositions->{$increment.'_seed_id'} = 0;
                            $bracketPositions->{$increment.'_seed_school'} = "BYE";
                            $bracketPositions->{$increment.'_seed_conference'} = "BYE";
                            break;
                        }
                        $bracketPositions->{$increment.'_seed'} = $playerOne->last_name.' / '.$playerTwo->last_name;
                        $bracketPositions->{$increment.'_seed_id'} = $team['id'];

                        if($playerOne->getSchool()->name === $playerTwo->getSchool()->name) {
                            $schoolName = $playerOne->getSchool()->name;
                            $conference = $playerOne->getSchool()->conference;
                        } else {
                            $schoolName = $playerOne->getSchool()->name . ' / ' . $playerTwo->getSchool()->name;
                            if($playerOne->getSchool()->conference === $playerTwo->getSchool()->conference) {
                                $conference = $playerOne->getSchool()->conference;
                            } else {
                                $conference = $playerOne->getSchool()->conference . ' / ' . $playerTwo->getSchool()->conference;
                            }
                        }

                        if($playerOne->id != 0) {
                            $bracketPositions->{$increment.'_seed_school'} = $schoolName;
                            $bracketPositions->{$increment.'_seed_conference'} = $conference;
                        } else {
                            $bracketPositions->{$increment.'_seed_school'} = $playerOne->school;
                            $bracketPositions->{$increment.'_seed_conference'} = $playerOne->conference;
                        }
                        break;
                    }
                }
            }

            foreach ($bracketPositionTitles as $title) {

                if ($bracketPositions->$title != 0) {
                    foreach($doublesTeams as $doublesTeam) {
                        if($doublesTeam['id'] === $bracketPositions->$title) {
                            $team = $doublesTeam;
                            break;
                        }
                    }
                    $playerOne = $team[0];
                    $playerTwo = $team[1];
                    $playersNamesCombined = $playerOne->last_name.' / '.$playerTwo->last_name;
                    if($playerOne->getSchool()->name === $playerTwo->getSchool()->name) {
                        $schoolName = $playerOne->getSchool()->name;
                    } else {
                        $schoolName = $playerOne->getSchool()->name . ' / ' . $playerTwo->getSchool()->name;
                    }
                    $bracketPositions->{$title} = $schoolName;
                    $bracketPositions->{$title.'_id'} = $team['id'];
                } else if ($bracketPositions->$title === 0) {
                    $bracketPositions->$title = "BYE";
                    $bracketPositions->{$title.'_id'} = 0;
                }
            }

            $matches = $allDoublesMatches->where('bracket', '=', $requestedBracket);

            return response()->json([
                'doublesTeams' => $doublesTeams,
                'bracketPositions' => $bracketPositions,
                'matches' => $matches,
                'courtCount' => $tournament->courts,
                'courtsInUse' => $courtsInUse
            ]);
        }
    }
}
