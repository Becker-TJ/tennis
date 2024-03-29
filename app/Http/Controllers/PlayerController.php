<?php

namespace App\Http\Controllers;

use App\DoublesTeam;
use App\Player;
use App\School;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class PlayerController extends Controller
{
    public function showFilteredPlayers()
    {
        $data = $_POST;

        if ($data['conference_setting'] == 'all_classes') {
            $players = DB::table('players')->join('schools', 'players.school_id', 'schools.id')
                ->where('players.gender', $data['gender'])
                ->where('players.'.$data['bracket_rank'], '>', 0)
                ->orderBy('players.'.$data['bracket_rank'], 'asc')
                ->get();
        } else {
            $players = DB::table('players')->join('schools', 'players.school_id', 'schools.id')
                ->where('schools.conference', $data['conference_setting'])
                ->where('players.gender', $data['gender'])
                ->where('players.'.$data['bracket_rank'], '>', 0)
                ->orderBy('players.'.$data['bracket_rank'], 'asc')
                ->get();
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
            'girls_one_singles_rank' => 'One Singles',
            'girls_two_singles_rank' => 'Two Singles',
            'girls_one_doubles_rank' => 'One Doubles',
            'girls_two_doubles_rank' => 'Two Doubles',
            'boys_one_singles_rank' => 'One Singles',
            'boys_two_singles_rank' => 'Two Singles',
            'boys_one_doubles_rank' => 'One Doubles',
            'boys_two_doubles_rank' => 'Two Doubles',
            'Male' => 'Boys',
            'Female' => 'Girls',

        ];
        $tableTitle = $cleanNamesForTableTitle[$radioButtonSettings['conference_setting']].
            ' '.
            $cleanNamesForTableTitle[$radioButtonSettings['gender']].
            ' '.
            $cleanNamesForTableTitle[$radioButtonSettings['bracket_rank']];

        return view('players', [
            'players' => $players,
            'radioButtonSettings' => $radioButtonSettings,
            'tableTitle' => $tableTitle,
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

        $tableTitle = 'All Boys One Singles';

        return view('players', [
            'players' => $players,
            'radioButtonSettings' => $radioButtonSettings,
            'tableTitle' => $tableTitle,
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

    public function create()
    {
        $data = $_POST;
        $players = Player::all();
        $schoolID = intval($data['school_id']);
        $lastPosition = $players->where('school_id', $schoolID)->max('position');

        if (! $lastPosition) {
            $lastPosition = 0;
        }

        $player = new Player;

        $player['first_name'] = $data['new_player_first_name'];
        $player['last_name'] = $data['new_player_last_name'];
        $player['position'] = $lastPosition + 1;
        $player['class'] = $data['class'];
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
