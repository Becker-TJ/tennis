<?php

namespace App;

use App\DoublesTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'host_id', 'location_name', 'team_count', 'gender', 'address', 'level', 'privacy_setting', 'date', 'time', 'courts'];

    public function getHost()
    {
        $school = School::where('id', $this->host_id)->first();

        return $school;
    }

    public function getDoublesSortedByTournamentSeed($requestedBracket) {
        $bracketPositions = BracketPosition::all()->where('tournament_id', '=', $this->id)->where('bracket', '=', $requestedBracket)->first();

        $doublesTeams = [];

        for ($increment = 1; $increment <= $this->team_count; $increment++) {
            $seed = $increment . "_seed";
            $doublesTeamID = $bracketPositions->$seed;

            $doublesTeam = DoublesTeam::find($doublesTeamID);
            if($doublesTeam != null) {
                $playerDetails = $doublesTeam->getPlayerDetails();
                $playerDetails['id'] = $doublesTeamID;
            } else {
                $playerOne = new Player;
                $playerOne->in_tournament = false;
                $playerOne->id = 0;
                $playerOne->first_name = "-";
                $playerOne->last_name = "";
                $playerOne->grade = "-";
                $playerOne->real_player = false;
                $playerOne->school = "-";
                $playerOne->conference = "-";
                $playerOne->first_name = "-";
                $playerOne->last_name = "-";

                $playerTwo = new Player;
                $playerTwo->in_tournament = false;
                $playerTwo->id = 0;
                $playerTwo->first_name = "-";
                $playerTwo->last_name = "";
                $playerTwo->grade = "-";
                $playerTwo->real_player = false;
                $playerTwo->school = "-";
                $playerTwo->conference = "-";
                $playerTwo->first_name = "-";
                $playerTwo->last_name = "-";

                $playerDetails = [$playerOne, $playerTwo];
                $playerDetails['id'] = 0;
            }

            //playerdetails[id] mayyyy be breaking something - for later

            $doublesTeams[] = $playerDetails;
        }

        return $doublesTeams;
    }


    public function getDoublesSortedByRank($requestedBracket)
    {
        $attendees = SchoolAttendee::all()->where('tournament_id', '=', $this->id);

        foreach ($attendees as $attendee) {
            $school = $attendee->getSchool();
            if ($requestedBracket === 'girlsOneDoubles') {
                $doublesTeam = $school->getGirlsOneDoublesTeam();
            } else if ($requestedBracket === 'girlsTwoDoubles') {
                $doublesTeam = $school->getGirlsTwoDoublesTeam();
            } else if ($requestedBracket === 'boysOneDoubles') {
                $doublesTeam = $school->getBoysOneDoublesTeam();
            } else if ($requestedBracket === 'boysTwoDoubles') {
                $doublesTeam = $school->getBoysTwoDoublesTeam();
            }

            $doublesTeams[$attendee->school_id] = $doublesTeam;
        }

        $stillSorting = true;
        $ranks = [];
        $doublesTeamClass = new DoublesTeam;

        foreach ($doublesTeams as &$team) {
            $playerOne = $team[0];
            $playerTwo = $team[1];
            if($playerOne != null && $playerTwo != null) {
                $existingDoublesTeam = $doublesTeamClass->findDoublesTeam($playerOne->id, $playerTwo->id);
            } else {
                $existingDoublesTeam = false;
            }

            if ($existingDoublesTeam) {
                $team['rank'] = $existingDoublesTeam->girls_one_doubles_rank;
                $ranks[] = $existingDoublesTeam->girls_one_doubles_rank;
                $team['id'] = $existingDoublesTeam->id;
            } else {
                $team['rank'] = 99999;
                $ranks[] = 99999;
                $team['id'] = 0;
            }
        }

        unset($team);

        sort($ranks, SORT_NUMERIC);
        $increment = 0;
        $sortedOneDoubles = [];
        do {
            foreach ($doublesTeams as $team) {
                if ((count($doublesTeams) > $increment) && ($team['rank'] === $ranks[$increment])) {
                    $sortedOneDoubles[] = $team;
                    $increment++;
                }
            }

            if (count($sortedOneDoubles) === count($doublesTeams)) {
                $stillSorting = false;
            }
        } while ($stillSorting);

        return $sortedOneDoubles;
    }

    public function updateBracketPositionsWithAllAttendees() {


        $tournament_id = $this->id;
        $attendees = SchoolAttendee::all()->where('tournament_id', '=', $tournament_id);
        $singles = false;

        $brackets = ['girlsOneSingles', 'girlsTwoSingles', 'girlsOneDoubles', 'girlsTwoDoubles', 'boysOneSingles', 'boysTwoSingles', 'boysOneDoubles', 'boysTwoDoubles'];

        foreach($brackets as $bracket) {
            switch ($bracket) {
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
                    $singles = false;
                    $gender = 'Male';
                    $position = [3, 4];
                    $sort = 'boys_one_doubles_rank';
                    break;
                case 'boysTwoDoubles':
                    $singles = false;
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
                    $singles = false;
                    $gender = 'Female';
                    $position = [3, 4];
                    $sort = 'girls_one_doubles_rank';
                    break;
                case 'girlsTwoDoubles':
                    $singles = false;
                    $gender = 'Female';
                    $sort = 'girls_two_doubles_rank';
                    break;
            }

            $attendeeSchoolIDs = [];
            foreach ($attendees as $attendee) {
                $attendeeSchoolIDs[] = $attendee->school_id;
            }

            $bracketPositions = BracketPosition::all()->where('tournament_id', '=', $tournament_id)->where('bracket', $bracket)->first();
            if($bracketPositions !== null) {
                $bracketPositions->delete();
            }
            $bracketPositions = new BracketPosition;
            $bracketPositions->tournament_id = $tournament_id;
            $bracketPositions->bracket = $bracket;

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


                $increment = 1;
                foreach ($singlesPlayers as $player) {
                    $seed = $increment.'_seed';
                    $bracketPositions->$seed = $player->id;
                    $increment++;
                }
                $bracketPositions->saveOrFail();

            } else {

                $doublesTeams = $this->getDoublesSortedByRank($bracket);

                $increment = 1;
                foreach ($doublesTeams as $team) {
                    $seed = $increment.'_seed';
                    $bracketPositions->$seed = $team['id'];
                    $increment++;
                }
                $bracketPositions->saveOrFail();

            }
        }
        return $bracketPositions;


















//        $tournament_id = $this->id;
//        $attendees = SchoolAttendee::all()->where('tournament_id', '=', $tournament_id);
//        $singles = false;
//
//        $brackets = ['girlsOneSingles', 'girlsTwoSingles', 'girlsOneDoubles', 'girlsTwoDoubles', 'boysOneSingles', 'boysTwoSingles', 'boysOneDoubles', 'boysTwoDoubles'];
//
//        foreach($brackets as $bracket) {
//            switch ($bracket) {
//                case 'boysOneSingles':
//                    $singles = true;
//                    $gender = 'Male';
//                    $position = [1];
//                    $sort = 'boys_one_singles_rank';
//                    break;
//                case 'boysTwoSingles':
//                    $singles = true;
//                    $gender = 'Male';
//                    $position = [2];
//                    $sort = 'boys_two_singles_rank';
//                    break;
//                case 'boysOneDoubles':
//                    $singles = false;
//                    $gender = 'Male';
//                    $position = [3, 4];
//                    $sort = 'boys_one_doubles_rank';
//                    break;
//                case 'boysTwoDoubles':
//                    $singles = false;
//                    $gender = 'Male';
//                    $position = [5, 6];
//                    $sort = 'boys_two_doubles_rank';
//                    break;
//                case 'girlsOneSingles':
//                    $singles = true;
//                    $gender = 'Female';
//                    $position = [1];
//                    $sort = 'girls_one_singles_rank';
//                    break;
//                case 'girlsTwoSingles':
//                    $singles = true;
//                    $gender = 'Female';
//                    $position = [2];
//                    $sort = 'girls_two_singles_rank';
//                    break;
//                case 'girlsOneDoubles':
//                    $singles = false;
//                    $gender = 'Female';
//                    $position = [3, 4];
//                    $sort = 'girls_one_doubles_rank';
//                    break;
//                case 'girlsTwoDoubles':
//                    $singles = false;
//                    $gender = 'Female';
//                    $sort = 'girls_two_doubles_rank';
//                    break;
//            }
//
//            $attendeeSchoolIDs = [];
//            foreach ($attendees as $attendee) {
//                $attendeeSchoolIDs[] = $attendee->school_id;
//            }
//
//            $bracketPositions = BracketPosition::all()->where('tournament_id', '=', $tournament_id)->where('bracket', $bracket)->first();
//            $bracketPositionClass = new BracketPosition;
//            $bracketPositionTitles = $bracketPositionClass->getFillable();
//            array_shift($bracketPositionTitles);
//            array_shift($bracketPositionTitles);
//
//            if ($singles) {
//                $singlesPlayers = Player::all()
//                    ->where('gender', '=', $gender)
//                    ->whereIn('school_id', $attendeeSchoolIDs)
//                    ->whereIn('position', $position)
//                    ->sortBy($sort);
//
//                if ($bracketPositions === null) {
//                    $bracketPositions = new BracketPosition();
//                    $bracketPositions->tournament_id = $tournament_id;
//                    $bracketPositions->bracket = $bracket;
//                }
//
//                $increment = 1;
//                foreach ($singlesPlayers as $player) {
//                    $seed = $increment.'_seed';
//                    $bracketPositions->$seed = $player->id;
//                    $increment++;
//                }
//                $bracketPositions->saveOrFail();
//
//            } else {
//
//                $doublesTeams = $this->getDoublesSortedByRank($bracket);
//
//                if ($bracketPositions == null) {
//                    $bracketPositions = new BracketPosition();
//                    $bracketPositions->tournament_id = $tournament_id;
//                    $bracketPositions->bracket = $bracket;
//                }
//                $increment = 1;
//                foreach ($doublesTeams as $team) {
//                    $seed = $increment.'_seed';
//                    $bracketPositions->$seed = $team['id'];
//                    $increment++;
//                }
//                $bracketPositions->saveOrFail();
//
//            }
//        }
//        return $bracketPositions;
    }
}
