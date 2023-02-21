<?php

namespace App\Http\Controllers;

use App\Player;
use App\School;
use Auth;
use Illuminate\Routing\Controller;

class SchoolController extends Controller
{
    public function __construct()
    {
    }

    public function showAddSchool()
    {
        $schools = School::all()->sortBy('name');

        return view('addschool', [
            'schools' => $schools,
        ]);
    }

    public function showSchools()
    {
        $schools = School::all();

        return view('schools', [
            'schools' => $schools,
        ]);
    }



    public function showSchool(School $school)
    {
        $boys = Player::all()->where('school_id', '=', $school->id)->where('gender', '=', 'Male');
        $girls = Player::all()->where('school_id', '=', $school->id)->where('gender', '=', 'Female');

        $positionNamesOrder = [
            '#1 Singles',
            '#2 Singles',
            '#1 Doubles',
            '#1 Doubles',
            '#2 Doubles',
            '#2 Doubles',
        ];

        foreach ($boys as $player) {
            array_push($positionNamesOrder, 'JV');
        }

        foreach($girls as $player) {
            array_push($positionNamesOrder, 'JV');
        }

        $increment = 0;


        // ORIGINAL CHECK
        if(Auth::check() && (Auth::user()->school_id === $school->id)) {
            $isCoach = true;
        } else {
            $isCoach = false;
        }

        // TEMPORARY EDIT, MUST FIX  - JUST USE ABOVE
        $isCoach = false;
        if(Auth::check() && (Auth::user()->school_id === $school->id)) {
            $isCoach = true;
        } else {
            if(Auth::check()) {
                $isCoach = true;
            }
        }

        return view('school', [
            'positionNamesOrder' => $positionNamesOrder,
            'school' => $school,
            'boys' => $boys,
            'girls' => $girls,
            'increment' => $increment,
            'isCoach' => $isCoach
        ]);
    }

    public function createOrTie()
    {
        if ($_POST['not_listed'] == 'true') {
            return $this->create();
        } else {
            return $this->tieUserToExistingSchool();
        }
    }

    public function create()
    {
        $data = $_POST;
        $school = new School;

        $school['name'] = $data['school_name'];
        $school['address'] = $data['school_address'];
        $school['conference'] = $data['school_class'];

        $school->saveOrFail();

        $lastInsertedId = $school->id;
        $this->tieUserToExistingSchool($lastInsertedId);

        $user = Auth::user();
        return redirect()->action([\App\Http\Controllers\SchoolController::class, 'showSchool'], ['school' => $user->school_id]);
    }

    public function tieUserToExistingSchool($id = false)
    {
        $user = Auth::user();

        if ($id) {
            $user->school_id = $id;
        } else {
            $user->school_id = $_POST['school_id_to_tie'];
        }

        $user->saveOrFail();
        return redirect()->action([\App\Http\Controllers\SchoolController::class, 'showSchool'], ['school' => $user->school_id]);
    }
}
