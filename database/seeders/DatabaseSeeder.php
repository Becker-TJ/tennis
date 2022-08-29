<?php

namespace Database\Seeders;

use App\DoublesTeam;
use App\Player;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Player::factory()->count(500)->create();
        \App\School::factory()->count(9)->create();
        \App\Tournament::factory()->count(50)->create();
        \App\User::factory()->count(9)->create();
        \App\SchoolAttendee::factory()->count(50)->create();

        $highSchools = $this->getArrayOfHighSchools();
        $conferenceOptions = ['3A', '4A', '5A', '6A'];
        foreach ($highSchools as $highschool) {
            $randomConferenceOption = $conferenceOptions[array_rand($conferenceOptions)];
            DB::table('schools')->insert([
                'name' => $highschool,
                'address' => 'Oklahoma City',
                'conference' => $randomConferenceOption,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
        }
        $girlsOneSinglesRank = 'girls_one_singles_rank';
        $girlsTwoSinglesRank = 'girls_two_singles_rank';
        $girlsOneDoublesRank = 'girls_one_doubles_rank';
        $girlsTwoDoublesRank = 'girls_two_doubles_rank';

        $lawtonEisenhowerOneSingles = new Player;
        $lawtonEisenhowerOneSingles['first_name'] = 'Genevieve';
        $lawtonEisenhowerOneSingles['last_name'] = 'Young';
        $lawtonEisenhowerOneSingles['position'] = 1;
        $lawtonEisenhowerOneSingles['grade'] = 'Senior';
        $lawtonEisenhowerOneSingles['gender'] = 'Female';
        $lawtonEisenhowerOneSingles[$girlsOneSinglesRank] = 1;
        $lawtonEisenhowerOneSingles[$girlsTwoSinglesRank] = 99999;
        $lawtonEisenhowerOneSingles['school_id'] = 513;
        $lawtonEisenhowerOneSingles->saveOrFail();
        $lawtonEisenhowerTwoSingles = new Player;
        $lawtonEisenhowerTwoSingles['first_name'] = 'Kaitlyn';
        $lawtonEisenhowerTwoSingles['last_name'] = 'Norman';
        $lawtonEisenhowerTwoSingles['position'] = 2;
        $lawtonEisenhowerTwoSingles['grade'] = 'Senior';
        $lawtonEisenhowerTwoSingles['gender'] = 'Female';
        $lawtonEisenhowerTwoSingles[$girlsOneSinglesRank] = 99999;
        $lawtonEisenhowerTwoSingles[$girlsTwoSinglesRank] = 99999;
        $lawtonEisenhowerTwoSingles['school_id'] = 513;
        $lawtonEisenhowerTwoSingles->saveOrFail();
        $lawtonEisenhowerOneDoublesPlayerOne = new Player;
        $lawtonEisenhowerOneDoublesPlayerOne['first_name'] = 'A';
        $lawtonEisenhowerOneDoublesPlayerOne['last_name'] = 'Amantine';
        $lawtonEisenhowerOneDoublesPlayerOne['position'] = 3;
        $lawtonEisenhowerOneDoublesPlayerOne['grade'] = 'Senior';
        $lawtonEisenhowerOneDoublesPlayerOne['gender'] = 'Female';
        $lawtonEisenhowerOneDoublesPlayerOne[$girlsOneSinglesRank] = 99999;
        $lawtonEisenhowerOneDoublesPlayerOne[$girlsTwoSinglesRank] = 99999;
        $lawtonEisenhowerOneDoublesPlayerOne['school_id'] = 513;
        $lawtonEisenhowerOneDoublesPlayerOne->saveOrFail();
        $lawtonEisenhowerOneDoublesPlayerTwo = new Player;
        $lawtonEisenhowerOneDoublesPlayerTwo['first_name'] = 'A';
        $lawtonEisenhowerOneDoublesPlayerTwo['last_name'] = 'Dixon';
        $lawtonEisenhowerOneDoublesPlayerTwo['position'] = 4;
        $lawtonEisenhowerOneDoublesPlayerTwo['grade'] = 'Senior';
        $lawtonEisenhowerOneDoublesPlayerTwo['gender'] = 'Female';
        $lawtonEisenhowerOneDoublesPlayerTwo[$girlsOneSinglesRank] = 99999;
        $lawtonEisenhowerOneDoublesPlayerTwo[$girlsTwoSinglesRank] = 99999;
        $lawtonEisenhowerOneDoublesPlayerTwo['school_id'] = 513;
        $lawtonEisenhowerOneDoublesPlayerTwo->saveOrFail();
        $lawtonEisenhowerTwoDoublesPlayerOne = new Player;
        $lawtonEisenhowerTwoDoublesPlayerOne['first_name'] = 'A';
        $lawtonEisenhowerTwoDoublesPlayerOne['last_name'] = 'Kousman';
        $lawtonEisenhowerTwoDoublesPlayerOne['position'] = 5;
        $lawtonEisenhowerTwoDoublesPlayerOne['grade'] = 'Senior';
        $lawtonEisenhowerTwoDoublesPlayerOne['gender'] = 'Female';
        $lawtonEisenhowerTwoDoublesPlayerOne[$girlsOneSinglesRank] = 99999;
        $lawtonEisenhowerTwoDoublesPlayerOne[$girlsTwoSinglesRank] = 99999;
        $lawtonEisenhowerTwoDoublesPlayerOne['school_id'] = 513;
        $lawtonEisenhowerTwoDoublesPlayerOne->saveOrFail();
        $lawtonEisenhowerTwoDoublesPlayerTwo = new Player;
        $lawtonEisenhowerTwoDoublesPlayerTwo['first_name'] = 'A';
        $lawtonEisenhowerTwoDoublesPlayerTwo['last_name'] = 'Rohner';
        $lawtonEisenhowerTwoDoublesPlayerTwo['position'] = 6;
        $lawtonEisenhowerTwoDoublesPlayerTwo['grade'] = 'Senior';
        $lawtonEisenhowerTwoDoublesPlayerTwo['gender'] = 'Female';
        $lawtonEisenhowerTwoDoublesPlayerTwo[$girlsOneSinglesRank] = 99999;
        $lawtonEisenhowerTwoDoublesPlayerTwo[$girlsTwoSinglesRank] = 99999;
        $lawtonEisenhowerTwoDoublesPlayerTwo['school_id'] = 513;
        $lawtonEisenhowerTwoDoublesPlayerTwo->saveOrFail();

        $doublesTeam = new DoublesTeam;
        $doublesTeam->player_1 = $lawtonEisenhowerOneDoublesPlayerOne->id;
        $doublesTeam->player_2 = $lawtonEisenhowerOneDoublesPlayerTwo->id;
        $doublesTeam->saveOrFail();

        $doublesTeam = new DoublesTeam;
        $doublesTeam->player_1 = $lawtonEisenhowerTwoDoublesPlayerOne->id;
        $doublesTeam->player_2 = $lawtonEisenhowerTwoDoublesPlayerTwo->id;
        $doublesTeam->saveOrFail();

        $delCityOneSingles = new Player;
        $delCityOneSingles['first_name'] = 'Olivia';
        $delCityOneSingles['last_name'] = 'Sparks';
        $delCityOneSingles['position'] = 1;
        $delCityOneSingles['grade'] = 'Senior';
        $delCityOneSingles['gender'] = 'Female';
        $delCityOneSingles[$girlsOneSinglesRank] = 8;
        $delCityOneSingles[$girlsTwoSinglesRank] = 99999;
        $delCityOneSingles['school_id'] = 317;
        $delCityOneSingles->saveOrFail();
        $delCityTwoSingles = new Player;
        $delCityTwoSingles['first_name'] = 'Laura';
        $delCityTwoSingles['last_name'] = 'Sparks';
        $delCityTwoSingles['position'] = 2;
        $delCityTwoSingles['grade'] = 'Senior';
        $delCityTwoSingles['gender'] = 'Female';
        $delCityTwoSingles[$girlsOneSinglesRank] = 99999;
        $delCityTwoSingles[$girlsTwoSinglesRank] = 99999;
        $delCityTwoSingles['school_id'] = 317;
        $delCityTwoSingles->saveOrFail();
        $delCityOneDoublesPlayerOne = new Player;
        $delCityOneDoublesPlayerOne['first_name'] = 'A';
        $delCityOneDoublesPlayerOne['last_name'] = 'Efunnuga';
        $delCityOneDoublesPlayerOne['position'] = 3;
        $delCityOneDoublesPlayerOne['grade'] = 'Senior';
        $delCityOneDoublesPlayerOne['gender'] = 'Female';
        $delCityOneDoublesPlayerOne[$girlsOneSinglesRank] = 99999;
        $delCityOneDoublesPlayerOne[$girlsTwoSinglesRank] = 99999;
        $delCityOneDoublesPlayerOne['school_id'] = 317;
        $delCityOneDoublesPlayerOne->saveOrFail();
        $delCityOneDoublesPlayerTwo = new Player;
        $delCityOneDoublesPlayerTwo['first_name'] = 'A';
        $delCityOneDoublesPlayerTwo['last_name'] = 'Miller';
        $delCityOneDoublesPlayerTwo['position'] = 4;
        $delCityOneDoublesPlayerTwo['grade'] = 'Senior';
        $delCityOneDoublesPlayerTwo['gender'] = 'Female';
        $delCityOneDoublesPlayerTwo[$girlsOneSinglesRank] = 99999;
        $delCityOneDoublesPlayerTwo[$girlsTwoSinglesRank] = 99999;
        $delCityOneDoublesPlayerTwo['school_id'] = 317;
        $delCityOneDoublesPlayerTwo->saveOrFail();
        $delCityTwoDoublesPlayerOne = new Player;
        $delCityTwoDoublesPlayerOne['first_name'] = 'A';
        $delCityTwoDoublesPlayerOne['last_name'] = 'Hudson';
        $delCityTwoDoublesPlayerOne['position'] = 5;
        $delCityTwoDoublesPlayerOne['grade'] = 'Senior';
        $delCityTwoDoublesPlayerOne['gender'] = 'Female';
        $delCityTwoDoublesPlayerOne[$girlsOneSinglesRank] = 99999;
        $delCityTwoDoublesPlayerOne[$girlsTwoSinglesRank] = 99999;
        $delCityTwoDoublesPlayerOne['school_id'] = 317;
        $delCityTwoDoublesPlayerOne->saveOrFail();
        $delCityTwoDoublesPlayerTwo = new Player;
        $delCityTwoDoublesPlayerTwo['first_name'] = 'A';
        $delCityTwoDoublesPlayerTwo['last_name'] = 'Fleehart';
        $delCityTwoDoublesPlayerTwo['position'] = 6;
        $delCityTwoDoublesPlayerTwo['grade'] = 'Senior';
        $delCityTwoDoublesPlayerTwo['gender'] = 'Female';
        $delCityTwoDoublesPlayerTwo[$girlsOneSinglesRank] = 99999;
        $delCityTwoDoublesPlayerTwo[$girlsTwoSinglesRank] = 99999;
        $delCityTwoDoublesPlayerTwo['school_id'] = 317;
        $delCityTwoDoublesPlayerTwo->saveOrFail();

        $doublesTeam = new DoublesTeam;
        $doublesTeam->player_1 = $delCityOneDoublesPlayerOne->id;
        $doublesTeam->player_2 = $delCityOneDoublesPlayerTwo->id;
        $doublesTeam->saveOrFail();

        $doublesTeam = new DoublesTeam;
        $doublesTeam->player_1 = $delCityTwoDoublesPlayerOne->id;
        $doublesTeam->player_2 = $delCityTwoDoublesPlayerTwo->id;
        $doublesTeam->saveOrFail();

        $PCNOneSingles = new Player;
        $PCNOneSingles['first_name'] = 'Winnie';
        $PCNOneSingles['last_name'] = 'Du';
        $PCNOneSingles['position'] = 1;
        $PCNOneSingles['grade'] = 'Senior';
        $PCNOneSingles['gender'] = 'Female';
        $PCNOneSingles[$girlsOneSinglesRank] = 5;
        $PCNOneSingles[$girlsTwoSinglesRank] = 99999;
        $PCNOneSingles['school_id'] = 340;
        $PCNOneSingles->saveOrFail();
        $PCNTwoSingles = new Player;
        $PCNTwoSingles['first_name'] = 'Delaney';
        $PCNTwoSingles['last_name'] = 'Fulp';
        $PCNTwoSingles['position'] = 2;
        $PCNTwoSingles['grade'] = 'Senior';
        $PCNTwoSingles['gender'] = 'Female';
        $PCNTwoSingles[$girlsOneSinglesRank] = 99999;
        $PCNTwoSingles[$girlsTwoSinglesRank] = 99999;
        $PCNTwoSingles['school_id'] = 340;
        $PCNTwoSingles->saveOrFail();
        $PCNOneDoublesPlayerOne = new Player;
        $PCNOneDoublesPlayerOne['first_name'] = 'A';
        $PCNOneDoublesPlayerOne['last_name'] = 'Stiger';
        $PCNOneDoublesPlayerOne['position'] = 3;
        $PCNOneDoublesPlayerOne['grade'] = 'Senior';
        $PCNOneDoublesPlayerOne['gender'] = 'Female';
        $PCNOneDoublesPlayerOne[$girlsOneSinglesRank] = 99999;
        $PCNOneDoublesPlayerOne[$girlsTwoSinglesRank] = 99999;
        $PCNOneDoublesPlayerOne['school_id'] = 340;
        $PCNOneDoublesPlayerOne->saveOrFail();
        $PCNOneDoublesPlayerTwo = new Player;
        $PCNOneDoublesPlayerTwo['first_name'] = 'A';
        $PCNOneDoublesPlayerTwo['last_name'] = 'Ughamada';
        $PCNOneDoublesPlayerTwo['position'] = 4;
        $PCNOneDoublesPlayerTwo['grade'] = 'Senior';
        $PCNOneDoublesPlayerTwo['gender'] = 'Female';
        $PCNOneDoublesPlayerTwo[$girlsOneSinglesRank] = 99999;
        $PCNOneDoublesPlayerTwo[$girlsTwoSinglesRank] = 99999;
        $PCNOneDoublesPlayerTwo['school_id'] = 340;
        $PCNOneDoublesPlayerTwo->saveOrFail();
        $PCNTwoDoublesPlayerOne = new Player;
        $PCNTwoDoublesPlayerOne['first_name'] = 'A';
        $PCNTwoDoublesPlayerOne['last_name'] = 'Phan';
        $PCNTwoDoublesPlayerOne['position'] = 5;
        $PCNTwoDoublesPlayerOne['grade'] = 'Senior';
        $PCNTwoDoublesPlayerOne['gender'] = 'Female';
        $PCNTwoDoublesPlayerOne[$girlsOneSinglesRank] = 99999;
        $PCNTwoDoublesPlayerOne[$girlsTwoSinglesRank] = 99999;
        $PCNTwoDoublesPlayerOne['school_id'] = 340;
        $PCNTwoDoublesPlayerOne->saveOrFail();
        $PCNTwoDoublesPlayerTwo = new Player;
        $PCNTwoDoublesPlayerTwo['first_name'] = 'A';
        $PCNTwoDoublesPlayerTwo['last_name'] = 'Ortega';
        $PCNTwoDoublesPlayerTwo['position'] = 6;
        $PCNTwoDoublesPlayerTwo['grade'] = 'Senior';
        $PCNTwoDoublesPlayerTwo['gender'] = 'Female';
        $PCNTwoDoublesPlayerTwo[$girlsOneSinglesRank] = 99999;
        $PCNTwoDoublesPlayerTwo[$girlsTwoSinglesRank] = 99999;
        $PCNTwoDoublesPlayerTwo['school_id'] = 340;
        $PCNTwoDoublesPlayerTwo->saveOrFail();

        $doublesTeam = new DoublesTeam;
        $doublesTeam->player_1 = $PCNOneDoublesPlayerOne->id;
        $doublesTeam->player_2 = $PCNOneDoublesPlayerTwo->id;
        $doublesTeam->saveOrFail();

        $doublesTeam = new DoublesTeam;
        $doublesTeam->player_1 = $PCNTwoDoublesPlayerOne->id;
        $doublesTeam->player_2 = $PCNTwoDoublesPlayerTwo->id;
        $doublesTeam->saveOrFail();

        $westmooreOneSingles = new Player;
        $westmooreOneSingles['first_name'] = 'Lua';
        $westmooreOneSingles['last_name'] = 'Huynh';
        $westmooreOneSingles['position'] = 1;
        $westmooreOneSingles['grade'] = 'Senior';
        $westmooreOneSingles['gender'] = 'Female';
        $westmooreOneSingles[$girlsOneSinglesRank] = 4;
        $westmooreOneSingles[$girlsTwoSinglesRank] = 99999;
        $westmooreOneSingles['school_id'] = 81;
        $westmooreOneSingles->saveOrFail();
        $westmooreTwoSingles = new Player;
        $westmooreTwoSingles['first_name'] = 'Madeline';
        $westmooreTwoSingles['last_name'] = 'Chaney';
        $westmooreTwoSingles['position'] = 2;
        $westmooreTwoSingles['grade'] = 'Senior';
        $westmooreTwoSingles['gender'] = 'Female';
        $westmooreTwoSingles[$girlsOneSinglesRank] = 99999;
        $westmooreTwoSingles[$girlsTwoSinglesRank] = 99999;
        $westmooreTwoSingles['school_id'] = 81;
        $westmooreTwoSingles->saveOrFail();
        $westmooreOneDoublesPlayerOne = new Player;
        $westmooreOneDoublesPlayerOne['first_name'] = 'A';
        $westmooreOneDoublesPlayerOne['last_name'] = 'Patel';
        $westmooreOneDoublesPlayerOne['position'] = 3;
        $westmooreOneDoublesPlayerOne['grade'] = 'Senior';
        $westmooreOneDoublesPlayerOne['gender'] = 'Female';
        $westmooreOneDoublesPlayerOne[$girlsOneSinglesRank] = 99999;
        $westmooreOneDoublesPlayerOne[$girlsTwoSinglesRank] = 99999;
        $westmooreOneDoublesPlayerOne['school_id'] = 81;
        $westmooreOneDoublesPlayerOne->saveOrFail();
        $westmooreOneDoublesPlayerTwo = new Player;
        $westmooreOneDoublesPlayerTwo['first_name'] = 'A';
        $westmooreOneDoublesPlayerTwo['last_name'] = 'Chakrabarty';
        $westmooreOneDoublesPlayerTwo['position'] = 4;
        $westmooreOneDoublesPlayerTwo['grade'] = 'Senior';
        $westmooreOneDoublesPlayerTwo['gender'] = 'Female';
        $westmooreOneDoublesPlayerTwo[$girlsOneSinglesRank] = 99999;
        $westmooreOneDoublesPlayerTwo[$girlsTwoSinglesRank] = 99999;
        $westmooreOneDoublesPlayerTwo['school_id'] = 81;
        $westmooreOneDoublesPlayerTwo->saveOrFail();
        $westmooreTwoDoublesPlayerOne = new Player;
        $westmooreTwoDoublesPlayerOne['first_name'] = 'A';
        $westmooreTwoDoublesPlayerOne['last_name'] = 'Nguyen';
        $westmooreTwoDoublesPlayerOne['position'] = 5;
        $westmooreTwoDoublesPlayerOne['grade'] = 'Senior';
        $westmooreTwoDoublesPlayerOne['gender'] = 'Female';
        $westmooreTwoDoublesPlayerOne[$girlsOneSinglesRank] = 99999;
        $westmooreTwoDoublesPlayerOne[$girlsTwoSinglesRank] = 99999;
        $westmooreTwoDoublesPlayerOne['school_id'] = 81;
        $westmooreTwoDoublesPlayerOne->saveOrFail();
        $westmooreTwoDoublesPlayerTwo = new Player;
        $westmooreTwoDoublesPlayerTwo['first_name'] = 'A';
        $westmooreTwoDoublesPlayerTwo['last_name'] = 'Pramod';
        $westmooreTwoDoublesPlayerTwo['position'] = 6;
        $westmooreTwoDoublesPlayerTwo['grade'] = 'Senior';
        $westmooreTwoDoublesPlayerTwo['gender'] = 'Female';
        $westmooreTwoDoublesPlayerTwo[$girlsOneSinglesRank] = 99999;
        $westmooreTwoDoublesPlayerTwo[$girlsTwoSinglesRank] = 99999;
        $westmooreTwoDoublesPlayerTwo['school_id'] = 81;
        $westmooreTwoDoublesPlayerTwo->saveOrFail();

        $doublesTeam = new DoublesTeam;
        $doublesTeam->player_1 = $westmooreOneDoublesPlayerOne->id;
        $doublesTeam->player_2 = $westmooreOneDoublesPlayerTwo->id;
        $doublesTeam->saveOrFail();

        $doublesTeam = new DoublesTeam;
        $doublesTeam->player_1 = $westmooreTwoDoublesPlayerOne->id;
        $doublesTeam->player_2 = $westmooreTwoDoublesPlayerTwo->id;
        $doublesTeam->saveOrFail();

        $mooreOneSingles = new Player;
        $mooreOneSingles['first_name'] = 'Kaytlyn';
        $mooreOneSingles['last_name'] = 'Hall';
        $mooreOneSingles['position'] = 1;
        $mooreOneSingles['grade'] = 'Senior';
        $mooreOneSingles['gender'] = 'Female';
        $mooreOneSingles[$girlsOneSinglesRank] = 3;
        $mooreOneSingles[$girlsTwoSinglesRank] = 99999;
        $mooreOneSingles['school_id'] = 80;
        $mooreOneSingles->saveOrFail();
        $mooreTwoSingles = new Player;
        $mooreTwoSingles['first_name'] = 'Ella';
        $mooreTwoSingles['last_name'] = 'Daugherty';
        $mooreTwoSingles['position'] = 2;
        $mooreTwoSingles['grade'] = 'Senior';
        $mooreTwoSingles['gender'] = 'Female';
        $mooreTwoSingles[$girlsOneSinglesRank] = 99999;
        $mooreTwoSingles[$girlsTwoSinglesRank] = 99999;
        $mooreTwoSingles['school_id'] = 80;
        $mooreTwoSingles->saveOrFail();
        $mooreOneDoublesPlayerOne = new Player;
        $mooreOneDoublesPlayerOne['first_name'] = 'Kristin';
        $mooreOneDoublesPlayerOne['last_name'] = 'Folsom';
        $mooreOneDoublesPlayerOne['position'] = 3;
        $mooreOneDoublesPlayerOne['grade'] = 'Senior';
        $mooreOneDoublesPlayerOne['gender'] = 'Female';
        $mooreOneDoublesPlayerOne[$girlsOneSinglesRank] = 99999;
        $mooreOneDoublesPlayerOne[$girlsTwoSinglesRank] = 99999;
        $mooreOneDoublesPlayerOne['school_id'] = 80;
        $mooreOneDoublesPlayerOne->saveOrFail();
        $mooreOneDoublesPlayerTwo = new Player;
        $mooreOneDoublesPlayerTwo['first_name'] = 'Kali';
        $mooreOneDoublesPlayerTwo['last_name'] = 'Mayer';
        $mooreOneDoublesPlayerTwo['position'] = 4;
        $mooreOneDoublesPlayerTwo['grade'] = 'Senior';
        $mooreOneDoublesPlayerTwo['gender'] = 'Female';
        $mooreOneDoublesPlayerTwo[$girlsOneSinglesRank] = 99999;
        $mooreOneDoublesPlayerTwo[$girlsTwoSinglesRank] = 99999;
        $mooreOneDoublesPlayerTwo['school_id'] = 80;
        $mooreOneDoublesPlayerTwo->saveOrFail();
        $mooreTwoDoublesPlayerOne = new Player;
        $mooreTwoDoublesPlayerOne['first_name'] = 'Kyra';
        $mooreTwoDoublesPlayerOne['last_name'] = 'Ackah-Mensah';
        $mooreTwoDoublesPlayerOne['position'] = 5;
        $mooreTwoDoublesPlayerOne['grade'] = 'Senior';
        $mooreTwoDoublesPlayerOne['gender'] = 'Female';
        $mooreTwoDoublesPlayerOne[$girlsOneSinglesRank] = 99999;
        $mooreTwoDoublesPlayerOne[$girlsTwoSinglesRank] = 99999;
        $mooreTwoDoublesPlayerOne['school_id'] = 80;
        $mooreTwoDoublesPlayerOne->saveOrFail();
        $mooreTwoDoublesPlayerTwo = new Player;
        $mooreTwoDoublesPlayerTwo['first_name'] = 'Keagan';
        $mooreTwoDoublesPlayerTwo['last_name'] = 'Cooper';
        $mooreTwoDoublesPlayerTwo['position'] = 6;
        $mooreTwoDoublesPlayerTwo['grade'] = 'Senior';
        $mooreTwoDoublesPlayerTwo['gender'] = 'Female';
        $mooreTwoDoublesPlayerTwo[$girlsOneSinglesRank] = 99999;
        $mooreTwoDoublesPlayerTwo[$girlsTwoSinglesRank] = 99999;
        $mooreTwoDoublesPlayerTwo['school_id'] = 80;
        $mooreTwoDoublesPlayerTwo->saveOrFail();

        $doublesTeam = new DoublesTeam;
        $doublesTeam->player_1 = $mooreOneDoublesPlayerOne->id;
        $doublesTeam->player_2 = $mooreOneDoublesPlayerTwo->id;
        $doublesTeam->saveOrFail();

        $doublesTeam = new DoublesTeam;
        $doublesTeam->player_1 = $mooreTwoDoublesPlayerOne->id;
        $doublesTeam->player_2 = $mooreTwoDoublesPlayerTwo->id;
        $doublesTeam->saveOrFail();

        $southmooreOneSingles = new Player;
        $southmooreOneSingles['first_name'] = 'Gracie';
        $southmooreOneSingles['last_name'] = 'Graham';
        $southmooreOneSingles['position'] = 1;
        $southmooreOneSingles['grade'] = 'Senior';
        $southmooreOneSingles['gender'] = 'Female';
        $southmooreOneSingles[$girlsOneSinglesRank] = 6;
        $southmooreOneSingles[$girlsTwoSinglesRank] = 99999;
        $southmooreOneSingles['school_id'] = 82;
        $southmooreOneSingles->saveOrFail();
        $southmooreTwoSingles = new Player;
        $southmooreTwoSingles['first_name'] = 'Cemiah';
        $southmooreTwoSingles['last_name'] = 'Avila';
        $southmooreTwoSingles['position'] = 2;
        $southmooreTwoSingles['grade'] = 'Senior';
        $southmooreTwoSingles['gender'] = 'Female';
        $southmooreTwoSingles[$girlsOneSinglesRank] = 99999;
        $southmooreTwoSingles[$girlsTwoSinglesRank] = 99999;
        $southmooreTwoSingles['school_id'] = 82;
        $southmooreTwoSingles->saveOrFail();
        $southmooreOneDoublesPlayerOne = new Player;
        $southmooreOneDoublesPlayerOne['first_name'] = 'A';
        $southmooreOneDoublesPlayerOne['last_name'] = 'Finks';
        $southmooreOneDoublesPlayerOne['position'] = 3;
        $southmooreOneDoublesPlayerOne['grade'] = 'Senior';
        $southmooreOneDoublesPlayerOne['gender'] = 'Female';
        $southmooreOneDoublesPlayerOne[$girlsOneSinglesRank] = 99999;
        $southmooreOneDoublesPlayerOne[$girlsTwoSinglesRank] = 99999;
        $southmooreOneDoublesPlayerOne['school_id'] = 82;
        $southmooreOneDoublesPlayerOne->saveOrFail();
        $southmooreOneDoublesPlayerTwo = new Player;
        $southmooreOneDoublesPlayerTwo['first_name'] = 'A';
        $southmooreOneDoublesPlayerTwo['last_name'] = 'Wilhelm';
        $southmooreOneDoublesPlayerTwo['position'] = 4;
        $southmooreOneDoublesPlayerTwo['grade'] = 'Senior';
        $southmooreOneDoublesPlayerTwo['gender'] = 'Female';
        $southmooreOneDoublesPlayerTwo[$girlsOneSinglesRank] = 99999;
        $southmooreOneDoublesPlayerTwo[$girlsTwoSinglesRank] = 99999;
        $southmooreOneDoublesPlayerTwo['school_id'] = 82;
        $southmooreOneDoublesPlayerTwo->saveOrFail();
        $southmooreTwoDoublesPlayerOne = new Player;
        $southmooreTwoDoublesPlayerOne['first_name'] = 'A';
        $southmooreTwoDoublesPlayerOne['last_name'] = 'Swartzberg';
        $southmooreTwoDoublesPlayerOne['position'] = 5;
        $southmooreTwoDoublesPlayerOne['grade'] = 'Senior';
        $southmooreTwoDoublesPlayerOne['gender'] = 'Female';
        $southmooreTwoDoublesPlayerOne[$girlsOneSinglesRank] = 99999;
        $southmooreTwoDoublesPlayerOne[$girlsTwoSinglesRank] = 99999;
        $southmooreTwoDoublesPlayerOne['school_id'] = 82;
        $southmooreTwoDoublesPlayerOne->saveOrFail();
        $southmooreTwoDoublesPlayerTwo = new Player;
        $southmooreTwoDoublesPlayerTwo['first_name'] = 'A';
        $southmooreTwoDoublesPlayerTwo['last_name'] = 'Nguyen';
        $southmooreTwoDoublesPlayerTwo['position'] = 6;
        $southmooreTwoDoublesPlayerTwo['grade'] = 'Senior';
        $southmooreTwoDoublesPlayerTwo['gender'] = 'Female';
        $southmooreTwoDoublesPlayerTwo[$girlsOneSinglesRank] = 99999;
        $southmooreTwoDoublesPlayerTwo[$girlsTwoSinglesRank] = 99999;
        $southmooreTwoDoublesPlayerTwo['school_id'] = 82;
        $southmooreTwoDoublesPlayerTwo->saveOrFail();

        $doublesTeam = new DoublesTeam;
        $doublesTeam->player_1 = $southmooreOneDoublesPlayerOne->id;
        $doublesTeam->player_2 = $southmooreOneDoublesPlayerTwo->id;
        $doublesTeam->saveOrFail();

        $doublesTeam = new DoublesTeam;
        $doublesTeam->player_1 = $southmooreTwoDoublesPlayerOne->id;
        $doublesTeam->player_2 = $southmooreTwoDoublesPlayerTwo->id;
        $doublesTeam->saveOrFail();

        $lawtonHighOneSingles = new Player;
        $lawtonHighOneSingles['first_name'] = 'Ivette';
        $lawtonHighOneSingles['last_name'] = 'Sarabia';
        $lawtonHighOneSingles['position'] = 1;
        $lawtonHighOneSingles['grade'] = 'Senior';
        $lawtonHighOneSingles['gender'] = 'Female';
        $lawtonHighOneSingles[$girlsOneSinglesRank] = 7;
        $lawtonHighOneSingles[$girlsTwoSinglesRank] = 99999;
        $lawtonHighOneSingles['school_id'] = 99;
        $lawtonHighOneSingles->saveOrFail();
        $lawtonHighTwoSingles = new Player;
        $lawtonHighTwoSingles['first_name'] = 'Devon';
        $lawtonHighTwoSingles['last_name'] = 'Buckmaster';
        $lawtonHighTwoSingles['position'] = 2;
        $lawtonHighTwoSingles['grade'] = 'Senior';
        $lawtonHighTwoSingles['gender'] = 'Female';
        $lawtonHighTwoSingles[$girlsOneSinglesRank] = 99999;
        $lawtonHighTwoSingles[$girlsTwoSinglesRank] = 99999;
        $lawtonHighTwoSingles['school_id'] = 99;
        $lawtonHighTwoSingles->saveOrFail();
        $lawtonHighOneDoublesPlayerOne = new Player;
        $lawtonHighOneDoublesPlayerOne['first_name'] = 'A';
        $lawtonHighOneDoublesPlayerOne['last_name'] = 'Kamper';
        $lawtonHighOneDoublesPlayerOne['position'] = 3;
        $lawtonHighOneDoublesPlayerOne['grade'] = 'Senior';
        $lawtonHighOneDoublesPlayerOne['gender'] = 'Female';
        $lawtonHighOneDoublesPlayerOne[$girlsOneSinglesRank] = 99999;
        $lawtonHighOneDoublesPlayerOne[$girlsTwoSinglesRank] = 99999;
        $lawtonHighOneDoublesPlayerOne['school_id'] = 99;
        $lawtonHighOneDoublesPlayerOne->saveOrFail();
        $lawtonHighOneDoublesPlayerTwo = new Player;
        $lawtonHighOneDoublesPlayerTwo['first_name'] = 'A';
        $lawtonHighOneDoublesPlayerTwo['last_name'] = 'Omusinde';
        $lawtonHighOneDoublesPlayerTwo['position'] = 4;
        $lawtonHighOneDoublesPlayerTwo['grade'] = 'Senior';
        $lawtonHighOneDoublesPlayerTwo['gender'] = 'Female';
        $lawtonHighOneDoublesPlayerTwo[$girlsOneSinglesRank] = 99999;
        $lawtonHighOneDoublesPlayerTwo[$girlsTwoSinglesRank] = 99999;
        $lawtonHighOneDoublesPlayerTwo['school_id'] = 99;
        $lawtonHighOneDoublesPlayerTwo->saveOrFail();
        $lawtonHighTwoDoublesPlayerOne = new Player;
        $lawtonHighTwoDoublesPlayerOne['first_name'] = 'A';
        $lawtonHighTwoDoublesPlayerOne['last_name'] = 'Smith';
        $lawtonHighTwoDoublesPlayerOne['position'] = 5;
        $lawtonHighTwoDoublesPlayerOne['grade'] = 'Senior';
        $lawtonHighTwoDoublesPlayerOne['gender'] = 'Female';
        $lawtonHighTwoDoublesPlayerOne[$girlsOneSinglesRank] = 99999;
        $lawtonHighTwoDoublesPlayerOne[$girlsTwoSinglesRank] = 99999;
        $lawtonHighTwoDoublesPlayerOne['school_id'] = 99;
        $lawtonHighTwoDoublesPlayerOne->saveOrFail();
        $lawtonHighTwoDoublesPlayerTwo = new Player;
        $lawtonHighTwoDoublesPlayerTwo['first_name'] = 'A';
        $lawtonHighTwoDoublesPlayerTwo['last_name'] = 'Zinnante';
        $lawtonHighTwoDoublesPlayerTwo['position'] = 6;
        $lawtonHighTwoDoublesPlayerTwo['grade'] = 'Senior';
        $lawtonHighTwoDoublesPlayerTwo['gender'] = 'Female';
        $lawtonHighTwoDoublesPlayerTwo[$girlsOneSinglesRank] = 99999;
        $lawtonHighTwoDoublesPlayerTwo[$girlsTwoSinglesRank] = 99999;
        $lawtonHighTwoDoublesPlayerTwo['school_id'] = 99;
        $lawtonHighTwoDoublesPlayerTwo->saveOrFail();

        $doublesTeam = new DoublesTeam;
        $doublesTeam->player_1 = $lawtonHighOneDoublesPlayerOne->id;
        $doublesTeam->player_2 = $lawtonHighOneDoublesPlayerTwo->id;
        $doublesTeam->saveOrFail();

        $doublesTeam = new DoublesTeam;
        $doublesTeam->player_1 = $lawtonHighTwoDoublesPlayerOne->id;
        $doublesTeam->player_2 = $lawtonHighTwoDoublesPlayerTwo->id;
        $doublesTeam->saveOrFail();

        $choctawOneSingles = new Player;
        $choctawOneSingles['first_name'] = 'Makensie';
        $choctawOneSingles['last_name'] = 'Butler';
        $choctawOneSingles['position'] = 1;
        $choctawOneSingles['grade'] = 'Senior';
        $choctawOneSingles['gender'] = 'Female';
        $choctawOneSingles[$girlsOneSinglesRank] = 2;
        $choctawOneSingles[$girlsTwoSinglesRank] = 99999;
        $choctawOneSingles['school_id'] = 298;
        $choctawOneSingles->saveOrFail();
        $choctawTwoSingles = new Player;
        $choctawTwoSingles['first_name'] = 'Kaylee';
        $choctawTwoSingles['last_name'] = 'Bjorkley';
        $choctawTwoSingles['position'] = 2;
        $choctawTwoSingles['grade'] = 'Senior';
        $choctawTwoSingles['gender'] = 'Female';
        $choctawTwoSingles[$girlsOneSinglesRank] = 99999;
        $choctawTwoSingles[$girlsTwoSinglesRank] = 99999;
        $choctawTwoSingles['school_id'] = 298;
        $choctawTwoSingles->saveOrFail();
        $choctawOneDoublesPlayerOne = new Player;
        $choctawOneDoublesPlayerOne['first_name'] = 'A';
        $choctawOneDoublesPlayerOne['last_name'] = 'Massengale';
        $choctawOneDoublesPlayerOne['position'] = 3;
        $choctawOneDoublesPlayerOne['grade'] = 'Senior';
        $choctawOneDoublesPlayerOne['gender'] = 'Female';
        $choctawOneDoublesPlayerOne[$girlsOneSinglesRank] = 99999;
        $choctawOneDoublesPlayerOne[$girlsTwoSinglesRank] = 99999;
        $choctawOneDoublesPlayerOne['school_id'] = 298;
        $choctawOneDoublesPlayerOne->saveOrFail();
        $choctawOneDoublesPlayerTwo = new Player;
        $choctawOneDoublesPlayerTwo['first_name'] = 'A';
        $choctawOneDoublesPlayerTwo['last_name'] = 'Thompson';
        $choctawOneDoublesPlayerTwo['position'] = 4;
        $choctawOneDoublesPlayerTwo['grade'] = 'Senior';
        $choctawOneDoublesPlayerTwo['gender'] = 'Female';
        $choctawOneDoublesPlayerTwo[$girlsOneSinglesRank] = 99999;
        $choctawOneDoublesPlayerTwo[$girlsTwoSinglesRank] = 99999;
        $choctawOneDoublesPlayerTwo['school_id'] = 298;
        $choctawOneDoublesPlayerTwo->saveOrFail();
        $choctawTwoDoublesPlayerOne = new Player;
        $choctawTwoDoublesPlayerOne['first_name'] = 'A';
        $choctawTwoDoublesPlayerOne['last_name'] = 'Perchez';
        $choctawTwoDoublesPlayerOne['position'] = 5;
        $choctawTwoDoublesPlayerOne['grade'] = 'Senior';
        $choctawTwoDoublesPlayerOne['gender'] = 'Female';
        $choctawTwoDoublesPlayerOne[$girlsOneSinglesRank] = 99999;
        $choctawTwoDoublesPlayerOne[$girlsTwoSinglesRank] = 99999;
        $choctawTwoDoublesPlayerOne['school_id'] = 298;
        $choctawTwoDoublesPlayerOne->saveOrFail();
        $choctawTwoDoublesPlayerTwo = new Player;
        $choctawTwoDoublesPlayerTwo['first_name'] = 'A';
        $choctawTwoDoublesPlayerTwo['last_name'] = 'Ross';
        $choctawTwoDoublesPlayerTwo['position'] = 6;
        $choctawTwoDoublesPlayerTwo['grade'] = 'Senior';
        $choctawTwoDoublesPlayerTwo['gender'] = 'Female';
        $choctawTwoDoublesPlayerTwo[$girlsOneSinglesRank] = 99999;
        $choctawTwoDoublesPlayerTwo[$girlsTwoSinglesRank] = 99999;
        $choctawTwoDoublesPlayerTwo['school_id'] = 298;
        $choctawTwoDoublesPlayerTwo->saveOrFail();

        $doublesTeam = new DoublesTeam;
        $doublesTeam->player_1 = $choctawOneDoublesPlayerOne->id;
        $doublesTeam->player_2 = $choctawOneDoublesPlayerTwo->id;
        $doublesTeam->saveOrFail();

        $doublesTeam = new DoublesTeam;
        $doublesTeam->player_1 = $choctawTwoDoublesPlayerOne->id;
        $doublesTeam->player_2 = $choctawTwoDoublesPlayerTwo->id;
        $doublesTeam->saveOrFail();

        $playerIDs = range(1, 48);
        $possibleSeeds = [1, 2, 3, 4, 5, 6, 7, 8, 1, 2, 3, 4, 5, 6, 7, 8, 1, 2, 3, 4, 5, 6, 7, 8, 1, 2, 3, 4, 5, 6, 7, 8, 1, 2, 3, 4, 5, 6, 7, 8, 1, 2, 3, 4, 5, 6, 7, 8];
        $positions = [1, 2, 3, 4, 5, 6];

        foreach ($playerIDs as $playerID) {
//            DB::table('tournament_seeds')->insert([
//                'seed' => array_pop($possibleSeeds),
//                'tournament_id' => 2,
//                'player_id' => $playerID,
//                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
//                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
//            ]);

            DB::table('tournament_positions')->insert([
                'position' => array_pop($positions),
                'tournament_id' => 2,
                'player_id' => $playerID,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);

            if (empty($positions)) {
                $positions = [1, 2, 3, 4, 5, 6];
            }
        }

        $schoolIDs = [513, 317, 340, 80, 81, 82, 99, 298];
        foreach ($schoolIDs as $schoolID) {
            DB::table('school_attendees')->insert([
                'invite_status' => 'accepted',
                'tournament_id' => 51,
                'school_id' => $schoolID,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
        }

        DB::table('tournaments')->insert([
            'name' => 'Moore Invitational',
            'host_id' => 80,
            'location_name' => 'Earlywine',
            'address' => 'Moore',
            'level' => 'Varsity',
            'team_count' => 8,
            'gender' => 'Both',
            'date' => '1991-05-21',
            'time' => '08:00:00',
            'privacy_setting' => 'Private',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'courts' => 12
        ]);

    }



    public function getArrayOfHighSchools()
    {
        $highSchoolsAsArray = explode(PHP_EOL, $this->listOfHighSchools);
        $tidyHighSchools = [];
        foreach ($highSchoolsAsArray as $highSchool) {
            //to strip away useless info and comma
            $highSchoolNameOnly = explode(',', $highSchool);
            array_push($tidyHighSchools, $highSchoolNameOnly[0]);
        }

        return $tidyHighSchools;
    }

    protected string $listOfHighSchools = 'Cave Springs High School, Bunch
Stilwell High School, Stilwell
Watts High School, Watts
Westville High School, Westville
Aline-Cleo High School, Aline
Burlington High School, Burlington
Cherokee High School, Cherokee
Timberlake High School, Helena
Atoka High School, Atoka
Caney High School, Caney
Stringtown High School, Stringtown
Tushka High School, Atoka
Balko High School, Balko
Beaver High School, Beaver
Forgan High School, Forgan
Turpin High School, Turpin
Elk City High School, Elk City
Erick High School, Erick
Merritt High School, Elk City
Sayre High School, Sayre
Canton High School, Canton
Geary High School, Geary
Okeene Junior-Senior High School, Okeene
Watonga High School, Watonga
Achille High School, Achille
Bennington High School, Bennington
Caddo High School, Caddo
Calera High School, Calera
Colbert High School, Colbert
Durant High School, Durant
Rock Creek High School, Bokchito
Silo High School, Durant
Anadarko High School, Anadarko
Apache High School, Apache
Binger-Oney High School, Binger
Carnegie High School, Carnegie
Cement High School, Cement
Cyril High School, Cyril
Fort Cobb-Broxton High School, Fort Cobb
Gracemont High School, Gracemont
Hinton High School, Hinton
Hydro-Eakly High School, Hydro
Lookeba-Sickles High School, Lookeba
Calumet High School, Calumet
El Reno High School, El Reno
Mustang High School, Mustang
Piedmont High School, Piedmont
Southwest Covenant School, Yukon
Union City High School, Union City
Yukon High School, Yukon
Ardmore High School, Ardmore
Dickson High School, Ardmore
Fox Junior-Senior High School, Fox
Healdton High School, Healdton
Lone Grove High School, Lone Grove
Plainview High School, Ardmore
Springer High School, Springer
Wilson High School, Wilson
Hulbert Junior-Senior High School, Hulbert
Markoma Christian School, Tahlequah
Sequoyah High School, Tahlequah
Tahlequah High School, Tahlequah
Keys High School, Park Hill
Valleyville high school, Fortsmith
Boswell High School, Boswell
Fort Towson High School, Fort Towson
Hugo High School, Hugo
Soper High School, Soper
Boise City High School, Boise City
Felt High School, Felt
Moore High School, Oklahoma City
Westmoore High School, Oklahoma City
Southmoore High School, Oklahoma City
Norman High School, Norman
Norman North High School, Norman
Lexington High School, Lexington
Little Axe High School, Norman
Noble High School, Noble
Coalgate High School, Coalgate
Olney High School, Clarita
Tupelo High School, Tupelo
Cache High School, Cache
Chattanooga High School, Chattanooga
Eisenhower High School, Lawton
Elgin High School, Elgin
Fletcher High School, Fletcher
Geronimo High School, Geronimo
Indiahoma High School, Indiahoma
Lawton Christian School, Lawton
Lawton High School, Lawton
MacArthur High School, Lawton
Sterling High School, Sterling
Big Pasture High School, Randlett
Temple High School, Temple
Walters High School, Walters
Bluejacket High School, Bluejacket
Ketchum High School, Ketchum
Vinita High School, Vinita
Welch Junior-Senior High School, Welch
White Oak High School, Vinita
Bristow High School, Bristow
Depew High School, Depew
Drumright High School, Drumright
Kellyville High School, Kellyville
Kiefer High School, Kiefer
Mannford High School, Mannford
Mounds High School, Mounds
Oilton High School, Oilton
Olive High School, Drumright
Sapulpa High School, Sapulpa
Arapaho High School, Arapaho
Butler High School, Butler
Clinton High School, Clinton
Thomas-Fay-Custer Unified High School, Thomas
Weatherford High School, Weatherford
Colcord High School, Colcord
Grove High School, Grove
Jay High School, Jay
Kansas High School, Kansas
Oaks-Mission High School, Oaks
Seiling Junior-Senior High School, Seiling
Taloga High School, Taloga
Vici High School, Vici
Leedey High School, Leedey
Arnett High School, Arnett
Fargo High School, Fargo
Gage High School, Gage
Shattuck Senior High School, Shattuck
Chisholm High School, Enid
Cimarron High School, Lahoma
Covington-Douglas High School, Covington
Drummond High School, Drummond
Enid High School, Enid
Garber High School, Garber
Kremlin-Hillsdale High School, Kremlin
Oklahoma Bible Academy, Enid
Pioneer-Pleasant Vale High School, Waukomis
Waukomis High School, Waukomis
Elmore City-Pernell High School, Elmore City
Lindsay High School, Lindsay
Maysville High School, Maysville
Paoli High School, Paoli
Pauls Valley High School, Pauls Valley
Stratford Junior-Senior High School, Stratford
Wynnewood High School, Wynnewood
Alex Junior-Senior High School, Alex
Amber-Pocasset High School, Amber
Bridge Creek High School, Blanchard
Chickasha High School, Chickasha
Minco High School, Minco
Ninnekah Senior High School, Ninnekah
Rush Springs High School, Rush Springs
Tuttle High School, Tuttle
Verden High School, Verden
Deer Creek-Lamont High School, Lamont
Medford High School, Medford
Pond Creek-Hunter Junior-Senior High School, Pond Creek
Granite High School, Granite
Lakeside School, Granite
Mangum High School, Magnum
Hollis High School, Hollis
Buffalo High School, Buffalo
Laverne High School, Laverne
Keota High School, Keota
Kinta High School, Kinta
McCurtain High School, McCurtain
Stigler High School, Stigler
Wetumka High School, Wetumka
Calvin High School, Calvin
Dustin High School, Dustin
Holdenville High School, Holdenville
Moss High School, Holdenville
Stuart High School, Stuart
Altus High School, Altus
Blair High School, Blair
Duke High School, Duke
Eldorado High School, Eldorado
Navajo High School, Altus
Olustee High School, Olustee
Ringling High School, Ringling
Ryan High School, Ryan
Waurika High School, Waurika
Coleman High School, Coleman
Milburn High School, Milburn
Mill Creek High School, Mill Creek
Tishomingo High School, Tishomingo
Wapanucka High School, Wapanucka
Blackwell High School, Blackwell
Braman High School, Braman
Newkirk High School, Newkirk
Ponca City High School, Ponca City
Tonkawa High School, Tonkawa
Cashion High School, Cashion
Dover High School, Dover
Hennessey High School, Hennessey
Kingfisher High School, Kingfisher
Lomega High School, Omega
Okarche High School, Okarche
Hobart High School, Hobart
Lone Wolf Junior-Senior High School, Lone Wolf
Mountain View-Gotebo High School, Mountain View
Snyder High School, Snyder
Panola High School, Panola
Red Oak High School, Red Oak
Wilburton High School, Wilburton
Arkoma High School, Arkoma
Bokoshe High School, Bokoshe
Buffalo Valley High School, Talihina
Cameron High School, Cameron
Heavener High School, Heavener
Howe High School, Howe
Le Flore High School, Le Flore
Panama High School, Panama
Pocola High School, Pocola
Poteau High School, Poteau
Spiro High School, Spiro
Talihina High School, Talihina
Whitesboro High School, Whitesboro
Wister High School, Wister
Agra High School, Agra
Carney High School, Carney
Chandler High School, Chandler
Davenport High School, Davenport
Meeker High School, Meeker
Prague High School, Prague
Stroud High School, Stroud
Wellston High School, Wellston
Coyle High School, Coyle
Crescent High School, Crescent
Guthrie High School, Guthrie
Mulhall-Orlando High School, Orlando
Marietta High School, Marietta
Thackerville High School, Thackerville
Turner High School, Burneyville
Fairview High School, Fairview
Ringwood High School, Ringwood
Kingston High School, Kingston
Madill High School, Madill
Adair High School, Adair
Chouteau-Mazie High School, Chouteau
Locust Grove High School, Locust Grove
Pryor High School, Pryor
Salina High School, Salina
Blanchard High School, Blanchard
Dibble High School, Dibble
Newcastle High School, Newcastle
Purcell High School, Purcell
Washington High School, Washington
Wayne High School, Wayne
Battiest High School, Battiest
Broken Bow High School, Broken Bow
Eagletown High School, Eagletown
Haworth High School, Haworth
Idabel High School, Idabel
Smithville High School, Smithville
Valliant High School, Valliant
Wright City High School, Wright City
Checotah High School, Checotah
Eufaula High School, Eufaula
Hanna High School, Hanna
Midway High School, Council Hill
Davis High School, Davis
Oklahoma School for the Deaf, Sulphur
Sulphur High School, Sulphur
Boynton High School, Boynton
Braggs High School, Braggs
Fort Gibson High School, Fort Gibson
Haskell High School, Haskell
Hilldale High School, Muskogee
Muskogee High School, Muskogee
Oktaha High School, Oktaha
Oklahoma School for the Blind, Muskogee
Porum High School, Porum
Warner High School, Warner
Webbers Falls High School, Webbers Falls
Billings High School, Billings
Frontier High School, Red Rock
Morrison High School, Morrison
Perry High School, Perry
Nowata High School, Nowata
Oklahoma Union High School, South Coffeyville
South Coffeyville High School, South Coffeyville
Boley High School, Boley
Graham High School, Weleetka
Mason High School, Mason
Okemah High School, Okemah
Paden High School, Paden
Weleetka High School, Weleetka
Choctaw High School, Choctaw
Deer Creek High School, Edmond
Capitol Hill High School, Oklahoma City
Classen School of Advanced Studies, Oklahoma City
Douglass High School, Oklahoma City
Dove Science Academy, Oklahoma City
Emerson High School, Oklahoma City
U. S. Grant High School, Oklahoma City
Harding Charter Preparatory High School, Oklahoma City
John Marshall High School, Oklahoma City
New John Marshall High School, Oklahoma City
Northeast Academy for Health Sciences and Engineering, Oklahoma City
Northwest Classen High School, Oklahoma City
Oklahoma Centennial High School, Oklahoma City
Southeast High School, Oklahoma City
Star Spencer High School, Spencer
Dungee High School, Spencer
John Wesley Charter School, Oklahoma City
Carl Albert High School, Midwest City
Del City High School, Del City
Midwest City High School, Midwest City
Bethany High School, Bethany
Bishop McGuinness High School, Oklahoma City
Casady School, Oklahoma City
Christian Heritage Academy, Del City
Crooked Oak High School, Oklahoma City
Crossings Christian School, Oklahoma City
Destiny Christian School, Del City
Edmond Memorial High School, Edmond
Edmond North High School, Edmond
Edmond Santa Fe High School, Edmond
Harrah High School, Harrah
Heritage Hall School, Oklahoma City
Jones High School, Jones
Luther High School, Luther
Millwood High School, Oklahoma City
Mount Saint Mary High School, Oklahoma City
Oklahoma Academy, Harrah
Oklahoma Christian School, Edmond
Oklahoma School of Science and Mathematics, Oklahoma City
Providence Hall Classical Christian School, Edmond
Putnam City High School, Oklahoma City
Putnam City North High School, Oklahoma City
Putnam City West High School, Oklahoma City
Western Heights High School, Oklahoma City
Beggs High School, Beggs
Dewar High School, Dewar
Henryetta High School, Henryetta
Morris High School, Morris
Okmulgee High School, Okmulgee
Preston High School, Preston
Schulter High School, Schulter
Wilson High School, Henryetta
Barnsdall High School, Barnsdall
Hominy High School, Hominy
Pawhuska High School, Pawhuska
Prue High School, Prue
Shidler High School, Shidler
Skiatook High School, Skiatook
Woodland High School, Fairfax
Wynona High School, Wynona
Afton High School, Afton
Commerce High School, Commerce
Fairland High School, Fairland
Miami High School, Miami
Picher-Cardin High School, Picher
Quapaw High School, Quapaw
Wyandotte High School, Wyandotte
Cleveland High School, Cleveland
Pawnee High School, Pawnee
Cushing High School, Cushing
Glencoe High School, Glencoe
Perkins-Tryon High School, Perkins
Ripley High School, Ripley
Stillwater High School, Stillwater
Yale High School, Yale
Canadian High School, Canadian
Crowder High School, Crowder
Haileyville High School, Haileyville
Hartshorne High School, Hartshorne
Indianola High School, Indianola
Kiowa High School, Kiowa
McAlester High School, McAlester
Pittsburg Public School, Pittsburg
Quinton High School, Quinton
Savanna High School, Savanna
McAlester Christian Academy, McAlester
Lakewood Christian High School, McAlester
Ada Senior High School, Ada
Allen High School, Allen
Byng High School, Ada
Latta High School, Ada
McLish High School, Fittstown
Roff High School, Roff
Stonewall High School, Stonewall
Vanoss High School, Ada
Liberty Academy, Shawnee
Asher High School, Asher
Bethel High School, Shawnee
Dale High School, Dale
Earlsboro High School, Earlsboro
Macomb High School, Macomb
Maud High School, Maud
McLoud High School, McLoud
Shawnee High School, Shawnee
Tecumseh High School, Tecumseh
Wanette High School, Wanette
Antlers High School, Antlers
Clayton High School, Clayton
Moyers High School, Moyers
Rattan High School, Rattan
Cheyenne High School, Cheyenne
Hammon High School, Hammon
Leedey High School, Leedey
Reydon High School, Reydon
Sweetwater High School, Sweetwater
Catoosa High School, Catoosa
Chelsea High School, Chelsea
Claremore High School, Claremore
Foyil High School, Foyil
Inola High School, Inola
Oologah-Talala High School, Oologah
Sequoyah High School, Claremore
Verdigris High School, Claremore
Bowlegs High School, Bowlegs
Butner High School, Cromwell
Konawa High School, Konawa
New Lima High School, Wewoka
Sasakwa High School, Sasakwa
Seminole High School, Seminole
Strother High School, Seminole
Varnum High School, Seminole
Wewoka High School, Wewoka
Central High School, Sallisaw
Gans High School, Gans
Gore High School, Gore
Muldrow High School, Muldrow
Roland High School, Roland
Sallisaw High School, Sallisaw
Vian High School, Vian
Bray-Doyle High School, Marlow
Central High High School, Marlow
Comanche High School, Comanche
Duncan High School, Duncan
Empire High School, Duncan
Marlow High School, Marlow
Velma-Alma High School, Velma
Goodwell High School, Goodwell
Guymon High School, Guymon
Hardesty High School, Hardesty
Hooker High School, Hooker
Texhoma High School, Texhoma
Tyrone High School, Tyrone
Yarbrough High School, Goodwell
Davidson High School, Davidson
Frederick High School, Frederick
Grandfield High School, Grandfield
Tipton High School, Tipton
Jenks High School, Jenks
Owasso High School, Owasso
Owasso Alternative High School, Owasso
Owasso Mid-High School, Owasso
Booker T. Washington High School, Tulsa
Central High School, Tulsa
East Central High School, Tulsa
Edison Preparatory School, Tulsa
McLain High School, Tulsa
Memorial High School, Tulsa
Nathan Hale High School, Tulsa
Will Rogers High School, Tulsa
Daniel Webster High School, Tulsa
Union High School, Tulsa
Berryhill High School, Tulsa
Bishop Kelley High School, Tulsa
Bixby High School, Bixby
Broken Arrow Senior High, Broken Arrow
Broken Arrow North Intermediate High School, Broken Arrow
Broken Arrow South Intermediate High School, Broken Arrow
Cascia Hall Preparatory School, Tulsa
Collinsville High School, Collinsville
Dove Science Academy, Tulsa
Glenpool High School, Glenpool
Liberty High School, Mounds
Holland Hall, Tulsa
Metro Christian Academy, Tulsa
Mingo Valley Christian School, Tulsa
Moriah Christian Academy, Sand Springs
Charles Page High School, Sand Springs
Saint Augustine Acamady, Tulsa
Sperry High School, Sperry
Tulsa School of Arts and Sciences, Tulsa
Wright Christian Academy, Tulsa
Coweta High School, Coweta
Destiny Christian Academy, Wagoner
Okay High School, Okay
Porter Consolidated High School, Porter
Wagoner High School, Wagoner
Bartlesville High School, Bartlesville
Caney Valley High School, Ramona
Copan High School, Copan
Dewey High School, Dewey
Wesleyan Christian High School, Bartlesville
Burns Flat-Dill City High School, Burns Fla
Canute High School, Canute
Cordell High School, Cordell
Corn Bible Academy, Corn
Blanche Thomas High School, Sentinel
Washita Heights High School, Corn
Alva High School, Alva
Freedom High School, Freedom
Waynoka High School, Waynoka
Fort Supply High School, Fort Supply
Mooreland High School, Mooreland
Sharon-Mutual High School, Mutual
Woodward High School, Woodward
Lawton Eisenhower, Lawton';
}
