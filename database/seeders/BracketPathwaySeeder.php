<?php

namespace Database\Seeders;

use App\DoublesTeam;
use App\Player;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BracketPathwaySeeder extends Seeder
{
    public function run()
    {
//        $eightTeamPathways = [
//            1 => [5,9],
//            2 => [5,9],
//            3 => [6,10],
//            4 => [6,10],
//            5 => [7,15],
//            6 => [7,15],
//            7 => [8,null],
//            9 => [11,13],
//            10 => [11,13],
//            11 => [12, null],
//            13 => [14, null],
//            15 => [16, null]
//        ];

        $eightTeamPathways = [
            1 => ['matchup-5-top', 'matchup-9-top'],
            2 => ['matchup-5-bottom', 'matchup-9-bottom'],
            3 => ['matchup-6-top', 'matchup-10-top'],
            4 => ['matchup-6-bottom', 'matchup-10-bottom'],
            5 => ['matchup-7-top', 'matchup-8-top'],
            6 => ['matchup-7-bottom', 'matchup-8-bottom'],
            7 => ['champion', null],
            8 => ['third-place', null],
            9 => ['matchup-11-top', 'matchup-12-top'],
            10 => ['matchup-11-bottom', 'matchup-12-bottom'],
            11 => ['fifth-place', null],
            12 => ['seventh-place', null],
        ];


        foreach($eightTeamPathways as $index => $pathway) {
            $winning_path = $pathway[0];
            $losing_path = $pathway[1];

            DB::table('bracket_pathways')->insert([
                'bracket_type' => 'eight-team',
                'matchup' => $index,
                'winning_path' => $winning_path,
                'losing_path' => $losing_path,
            ]);
        }

        $eightTeamSeedToMatchupPositions = [
            '1_seed' => 'matchup-1-top',
            '2_seed' => 'matchup-4-bottom',
            '3_seed' => 'matchup-3-top',
            '4_seed' => 'matchup-2-bottom',
            '5_seed' => 'matchup-2-top',
            '6_seed' => 'matchup-3-bottom',
            '7_seed' => 'matchup-4-top',
            '8_seed' => 'matchup-1-bottom',
        ];

        foreach($eightTeamSeedToMatchupPositions as $seed => $matchupPosition) {
            DB::table('seed_to_matchup_positions')->insert([
                'bracket_type' => 'eight-team',
                'seed' => $seed,
                'matchup_position' => $matchupPosition,
            ]);
        }


    }



}
