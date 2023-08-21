<?php

namespace App\Http\Controllers;

use App\BracketPathway;
use App\BracketPosition;
use App\Court;
use App\DoublesTeam;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PlayerController;
use App\Player;
use App\School;
use App\Result;
use App\SchoolAttendee;
use App\SeedToMatchupPosition;
use App\Tournament;
use Illuminate\Http\Request;
use Auth;

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
        if($schoolAttendee === null) {
            $schoolAttendee = new SchoolAttendee;
            $schoolAttendee->school_id = $userSchoolID;
            $schoolAttendee->tournament_id = $tournamentID;
        }
        $schoolAttendee->invite_status = 'accepted';
        $schoolAttendee->saveOrFail();

        $tournament = Tournament::find($tournamentID);
        $tournament->updateBracketPositionsWithAllAttendees();

        return response()->json(['redirect_url'=> url('tournament/' . $tournamentID)]);
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
            'girlsOneSingles' => '#1 Singles',
            'girlsTwoSingles' => '#2 Singles',
            'girlsOneDoubles' => '#1 Doubles',
            'girlsTwoDoubles' => '#2 Doubles',
            'boysOneSingles' => '#1 Singles',
            'boysTwoSingles' => '#2 Singles',
            'boysOneDoubles' => '#1 Doubles',
            'boysTwoDoubles' => '#2 Doubles'
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
                if($prettyName === "#1 Doubles") {
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

        if(!$foundAPlayerInPreviousBracket && $bracketsPrettyPrintAssociations[$previousBracket] === "#2 Doubles") {
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

    public function inviteSchools(Request $request)
    {
        $input = $request->all();
        $inviteeSchoolIDs = $input['inviteeSchoolIDs'];
        $inviteStatuses = $input['inviteStatuses'];
        $tournament_id = intval($input['tournament_id']);
        $tournament = Tournament::find($tournament_id);

        $attendees = SchoolAttendee::all()->where('tournament_id', '=', $tournament_id);
        $updatedSchoolIDs = [];

        foreach($inviteeSchoolIDs as $key => $ID) {
            $school_id = intval($ID);
            $invite_status = $inviteStatuses[$key];
            if($invite_status === 'not sent') {
//                $invite_status = 'pending';
                //TEMPORARY EDIT, MUST FIX
                $invite_status = 'accepted';
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
        $schoolRemoved = false;
        foreach($attendees as $attendee) {
            if(in_array(intval($attendee->school_id), $updatedSchoolIDs)) {
                continue;
            } else {
                $attendee->delete();
                $schoolRemoved = true;
            }
        }
        if($schoolRemoved) {
            $tournament->updateBracketPositionsWithAllAttendees();
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

    public function saveCourt(Request $request) {
        $request = $request->all();
        $tournamentID = intval($request['tournament_id']);
        $matchup = intval($request['matchup']);
        $bracket = $request['bracket'];
        $courtSelection = intval($request['courtSelection']);

        $court = Court::where('tournament_id', $tournamentID)
            ->where('bracket', $bracket)
            ->where('matchup', $matchup)
            ->first();
        if($court === null) {
            $court = new Court;
            $court->tournament_id = $tournamentID;
            $court->bracket = $bracket;
            $court->matchup = $matchup;
        }

        $court->court = $courtSelection;
        $court->saveOrFail();

        return response()->json(['success'=>'Saved Court Assignment.']);
    }

    public function checkIfCoach(Request $request) {
        $schoolID = intval($request['school_id']);
        if(Auth::check() && (Auth::user()->school_id === $schoolID)) {
            $isCoach = true;
        } else {
            $isCoach = false;
        }

        return response()->json([
            'isCoach' => $isCoach
        ]);
    }

    public function getPlayerStats(Request $request) {
        $request = $request->all();
        $player = Player::find(intval($request['playerID']));
        $singlesMatches = SinglesMatch::select("*")->where('winner', '=', $player->id)->orWhere('loser', '=', $player->id)->get()->sortBy('date')->sortBy('bracket');
        $doublesTeams = DoublesTeam::select("*")->where('player_1', '=', $player->id)->orWhere('player_2', '=', $player->id)->get();
        $doublesMatches = collect([]);

        $bracketsPrettyPrintAssociations = [
            'girlsOneSingles' => '#1 Singles',
            'girlsTwoSingles' => '#2 Singles',
            'girlsOneDoubles' => '#1 Doubles',
            'girlsTwoDoubles' => '#2 Doubles',
            'boysOneSingles' => '#1 Singles',
            'boysTwoSingles' => '#2 Singles',
            'boysOneDoubles' => '#1 Doubles',
            'boysTwoDoubles' => '#2 Doubles'
        ];

        foreach($doublesTeams as $doublesTeam) {

            $player_1 = Player::find($doublesTeam->player_1);
            $player_2 = Player::find($doublesTeam->player_2);

            $doublesTeamPlayerNames = $player_1->last_name . ' / ' . $player_2->last_name;

            $doublesMatchesForSpecificTeam = DoublesMatch::select("*")->where('winner', '=', $doublesTeam->id)->orWhere('loser', '=', $doublesTeam->id)->get()->sortBy('date')->sortBy('bracket');
            foreach($doublesMatchesForSpecificTeam as $match) {
                $match->home_team = $doublesTeamPlayerNames;
                if($match->winner === $doublesTeam->id) {
                    $opponentID = $match->loser;
                    $match->winOrLoss = "W";
                } else {
                    $opponentID = $match->winner;
                    $match->winOrLoss = "L";
                }

                $opponentDoublesTeam = DoublesTeam::find($opponentID);
                $opponent_1 = Player::find($opponentDoublesTeam->player_1);
                $opponent_2 = Player::find($opponentDoublesTeam->player_2);
                $match->opponent = $opponent_1->last_name . ' / ' . $opponent_2->last_name;
                if($opponent_1->getSchool()->name === $opponent_2->getSchool()->name) {
                    $opponentSchoolName = $opponent_1->getSchool()->name;
                } else {
                    $opponentSchoolName = $opponent_1->getSchool()->name . ' / ' . $opponent_2->getSchool()->name;
                }
                $match->opponent_school = $opponentSchoolName;
                $match->bracket = $bracketsPrettyPrintAssociations[$match->bracket];
            }
            $doublesMatches = $doublesMatches->merge($doublesMatchesForSpecificTeam);
        }



        foreach($singlesMatches as $match) {
            if($match->winner === $player->id) {
                $opponentID = $match->loser;
                $match->winOrLoss = "W";
            } else {
                $opponentID = $match->winner;
                $match->winOrLoss = "L";
            }

            $opponent = Player::find($opponentID);
            $match->opponent = $opponent->first_name . ' ' . $opponent->last_name;
            $match->opponent_school = $opponent->getSchool()->name;
            $match->bracket = $bracketsPrettyPrintAssociations[$match->bracket];
            $match->home_team = $player->first_name . ' ' . $player->last_name;
        }


        $matches = $singlesMatches->merge($doublesMatches);

        return $matches;
    }

    public function getDoublesStats(Request $request) {
        $request = $request->all();
        $doublesTeam = DoublesTeam::find(intval($request['playerID']));
        $doublesMatches = DoublesMatch::select("*")->where('winner', '=', $doublesTeam->id)->orWhere('loser', '=', $doublesTeam->id)->get()->sortBy('date')->sortBy('bracket');

        $bracketsPrettyPrintAssociations = [
            'girlsOneSingles' => '#1 Singles',
            'girlsTwoSingles' => '#2 Singles',
            'girlsOneDoubles' => '#1 Doubles',
            'girlsTwoDoubles' => '#2 Doubles',
            'boysOneSingles' => '#1 Singles',
            'boysTwoSingles' => '#2 Singles',
            'boysOneDoubles' => '#1 Doubles',
            'boysTwoDoubles' => '#2 Doubles'
        ];

        $player_1 = Player::find($doublesTeam->player_1);
        $player_2 = Player::find($doublesTeam->player_2);

        $doublesTeamPlayerNames = $player_1->last_name . ' / ' . $player_2->last_name;

        foreach($doublesMatches as $match) {
            $match->home_team = $doublesTeamPlayerNames;
            if($match->winner === $doublesTeam->id) {
                $opponentID = $match->loser;
                $match->winOrLoss = "W";
            } else {
                $opponentID = $match->winner;
                $match->winOrLoss = "L";
            }

            $opponentDoublesTeam = DoublesTeam::find($opponentID);
            $opponent_1 = Player::find($opponentDoublesTeam->player_1);
            $opponent_2 = Player::find($opponentDoublesTeam->player_2);
            $match->opponent = $opponent_1->last_name . ' / ' . $opponent_2->last_name;
            if($opponent_1->getSchool()->name === $opponent_2->getSchool()->name) {
                $opponentSchoolName = $opponent_1->getSchool()->name;
            } else {
                $opponentSchoolName = $opponent_1->getSchool()->name . ' / ' . $opponent_2->getSchool()->name;
            }
            $match->opponent_school = $opponentSchoolName;
            $match->bracket = $bracketsPrettyPrintAssociations[$match->bracket];
        }

    return $doublesMatches;
    }

    public function getBracketData(Request $request)
    {
        $request = $request->all();
        $tournament_id = $request['tournament_id'];
        $tournament = Tournament::find($tournament_id);
        $attendees = SchoolAttendee::all()->where('tournament_id', '=', $tournament_id);
        $singles = false;
        $allResults = Result::all()->where('tournament_id', '=', $tournament_id);
        $seedToMatchupPositions = SeedToMatchupPosition::all()->where('bracket_type', 'eight-team');
        $bracketPathways = BracketPathway::all()->where('bracket_type', 'eight-team');




        $requestedBracket = $request['requestedBracket'];
        //TODO FIX
        $requestedBracket = 'girlsOneSingles';

        $courtsInUse = Court::all()->where('tournament_id', $tournament_id);

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
                        $player->firstAndLastName = $player->first_name.' '.$player->last_name;
                        $player->conference = $player->getSchool()->conference;

                        foreach($seedToMatchupPositions as $seedToMatchupPosition) {
                            if($seedToMatchupPosition->seed === $increment . '_seed') {
                                $bracketPositions->{$seedToMatchupPosition->matchup_position} = $player;
                            }
                        }

                        break;
                    }
                }
            }

            $results = $allResults->where('bracket', '=', $requestedBracket);

            foreach($results as $result) {
                $bracketPathway = $bracketPathways->first(function ($pathway) use ($result) {
                    return $pathway->matchup === $result->matchup;
                });

                $winningPlayer = $singlesPlayers->where('id', $result->winner)->first();
                $winningPlayer->firstAndLastName = $winningPlayer->first_name.' '.$winningPlayer->last_name;
                $winningPlayer->conference = $winningPlayer->getSchool()->conference;

                $losingPlayer = $singlesPlayers->where('id', $result->loser)->first();
                $losingPlayer->firstAndLastName = $losingPlayer->first_name.' '.$losingPlayer->last_name;
                $losingPlayer->conference = $losingPlayer->getSchool()->conference;

                if($bracketPathway->winning_path) {
                    $bracketPositions->{$bracketPathway->winning_path} = $winningPlayer;
                }
                if($bracketPathway->losing_path) {
                    $bracketPositions->{$bracketPathway->losing_path} = $losingPlayer;
                }
            }

            return response()->json([
                'players' => $singlesPlayers,
                'bracketPositions' => $bracketPositions,
                'matches' => $results,
                'courtCount' => $tournament->courts,
                'courtsInUse' => $courtsInUse,
                'teamCount' => $tournament->team_count
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

            $matches = $allMatches->where('bracket', '=', $requestedBracket);

            return response()->json([
                'doublesTeams' => $doublesTeams,
                'bracketPositions' => $bracketPositions,
                'matches' => $matches,
                'courtCount' => $tournament->courts,
                'courtsInUse' => $courtsInUse,
                'teamCount' => $tournament->team_count
            ]);
        }
    }
}
