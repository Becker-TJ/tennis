<?php

namespace App\Http\Controllers;

use App\DoublesTeam;
use App\Player;
use App\School;
use DB;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;

class
PlayerController extends Controller
{
    public function showFilteredPlayers()
    {
        $data = $_POST;
        if(str_contains($data['bracket_rank'], 'singles')) {
            $singles = true;
        } else {
            $singles = false;
        }

        if($singles) {
            $singles = 'true';
            if ($data['conference_setting'] == 'all_classes') {

                $players = Player::all()->where('gender', $data['gender'])->where($data['bracket_rank'], '>', 0)->sortBy($data['bracket_rank']);
                foreach($players as $player) {
                    $school = $player->getSchool();
                    $player->name = $school->name;
                    $player->address = $school->address;
                    $player->conference = $school->conference;
                }


            } else {

                $players = Player::all()->where('gender', $data['gender'])->where($data['bracket_rank'], '>', 0)->sortBy($data['bracket_rank']);
                $correctPlayers = Collection::make([]);
                foreach($players as $player) {
                    $school = $player->getSchool();
                    if($school->conference != $data['conference_setting']) {
                        continue;
                    } else {
                        $player->name = $school->name;
                        $player->address = $school->address;
                        $player->conference = $school->conference;
                        $correctPlayers[] = $player;
                    }
                }
                $players = $correctPlayers;
            }
        } else {
            $singles = 'false';
            $players = Collection::make([]);
            $doublesTeams = DoublesTeam::all()->sortBy($data['bracket_rank']);
            foreach($doublesTeams as $team) {
                $squad = $team->getPlayerDetails();
                $playerOne = $squad[0];
                $playerTwo = $squad[1];
                $school = $playerOne->getSchool();
                $teamDetails = new Player;
                $teamDetails->first_name = $playerOne->last_name . ' / ';
                $teamDetails->last_name = $playerTwo->last_name;
                $teamDetails->conference = $school->conference;
                $teamDetails->name = $school->name;
                $teamDetails->{$data['bracket_rank']} = $team->{$data['bracket_rank']};
                $teamDetails->school_id = $school->id;
                $teamDetails->id = $team->id;
                $players[] = $teamDetails;
            }
        }


        $radioButtonSettings = [
            'conference_setting' => $data['conference_setting'],
            'bracket_rank' => $data['bracket_rank'],
            'gender' => $data['gender'],
        ];
        $cleanNamesForTableTitle = [
            'all_classes' => 'All',
            '6A' => '6A',
            '5A' => '5A',
            '4A' => '4A',
            '3A' => '3A',
            'girls_one_singles_rank' => '#1 Singles',
            'girls_two_singles_rank' => '#2 Singles',
            'girls_one_doubles_rank' => '#1 Doubles',
            'girls_two_doubles_rank' => '#2 Doubles',
            'boys_one_singles_rank' => '#1 Singles',
            'boys_two_singles_rank' => '#2 Singles',
            'boys_one_doubles_rank' => '#1 Doubles',
            'boys_two_doubles_rank' => '#2 Doubles',
            'Male' => 'Boys',
            'Female' => 'Girls',

        ];
        $tableTitle = $cleanNamesForTableTitle[$radioButtonSettings['conference_setting']].
            ' '.
            $cleanNamesForTableTitle[$radioButtonSettings['gender']].
            ' '.
            $cleanNamesForTableTitle[$radioButtonSettings['bracket_rank']];

        return view('players', [
            'bracket_rank' => $data['bracket_rank'],
            'players' => $players,
            'radioButtonSettings' => $radioButtonSettings,
            'tableTitle' => $tableTitle,
            'singles' => $singles
        ]);
    }

    public function showAllPlayers()
    {
        $players = DB::table('players')->join('schools', 'players.school_id', 'schools.id')
            ->where('players.gender', 'Male')
            ->orderBy('players.boys_one_singles_rank', 'asc')
            ->get();

        $radioButtonSettings = [
            'conference_setting' => 'all_classes',
            'bracket_rank' => 'boys_one_singles_rank',
            'gender' => 'Male',
        ];

        $tableTitle = 'All Boys #1 Singles';
        $bracketRank = 'boys_one_singles_rank';

        return view('players', [
            'players' => $players,
            'radioButtonSettings' => $radioButtonSettings,
            'tableTitle' => $tableTitle,
            'bracket_rank' => $bracketRank,
            'singles' => 'true'
        ]);
    }

    public function showSchool($school_id = 0)
    {
        $players = Player::all();
        $players = $players->where('school_id', $school_id);

        return view('school', [
            'players' => $players,
        ]);
    }

    public function savePositionChanges($playerID, $newPosition)
    {
        $player = Player::find($playerID);
        $player['position'] = $newPosition;

        return $player->saveOrFail();
    }

    public function editPlayer() {
        $data = $_POST;
        $playerID = $data['edit_player_id'];
        $player = Player::find($playerID);
        $player->first_name = $data['edit_player_first_name'];
        $player->last_name = $data['edit_player_last_name'];
        $player->grade = $data['edit_grade'];
        $player->gender = $data['edit_gender'];
        $player->saveOrFail();

        return redirect()->action([\App\Http\Controllers\SchoolController::class, 'showSchool'], ['school' => intval($data['school_id'])]);
    }

    public function create()
    {
        $data = $_POST;
        $players = Player::all();
        $schoolID = intval($data['school_id']);
        $lastPosition = $players->where('school_id', $schoolID)->where('gender', '=', $data['gender'])->max('position');

        if (! $lastPosition) {
            $lastPosition = 0;
        }

        $player = new Player;

        $player['first_name'] = $data['new_player_first_name'];
        $player['last_name'] = $data['new_player_last_name'];
        $player['position'] = $lastPosition + 1;
        $player['grade'] = $data['grade'];
        $player['gender'] = $data['gender'];
        $player['boys_one_singles_rank'] = 99999;
        $player['boys_two_singles_rank'] = 99999;
        $player['girls_one_singles_rank'] = 99999;
        $player['girls_two_singles_rank'] = 99999;
        $player['school_id'] = $schoolID;

        $player->saveOrFail();

        $schoolPlayers = $players->where('school_id', '=', $schoolID)->sortBy('position');

        if ($schoolPlayers->count() > 2) {
            if ($player['position'] === 4) {
                $playerAtPositionThree = $schoolPlayers->where('position', '=', 3)->first();

                $newDoublesTeam = new DoublesTeam;
                $newDoublesTeam->player_1 = $playerAtPositionThree->id;
                $newDoublesTeam->player_2 = $player->id;
                $newDoublesTeam->saveDoublesTeam();
            }

            if ($player['position'] === 6) {
                $playerAtPositionFive = $schoolPlayers->where('position', '=', 5)->first();

                $newDoublesTeam = new DoublesTeam;
                $newDoublesTeam->player_1 = $playerAtPositionFive->id;
                $newDoublesTeam->player_2 = $player->id;
                $newDoublesTeam->saveDoublesTeam();
            }
        }

        return redirect()->route('school', ['school' => $schoolID]);
    }
}
