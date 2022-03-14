<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Routing\Controller;
use App\School;
use App\Player;

class SchoolController extends Controller
{
    public function __construct()
    {

    }

    public function showAddSchool() {
        $schools = School::all();
        return view('addschool', [
            'schools' => $schools
        ]);
    }

    public function showSchools() {
        $schools = School::all();
        return view('schools', [
            'schools' => $schools
        ]);
    }

    public function showSchool(School $school) {
        $players = Player::all()->where('school_id', '=', $school->id);

        $positionNamesOrder = [
            '1 Singles',
            '2 Singles',
            '1 Doubles',
            '1 Doubles',
            '2 Doubles',
            '2 Doubles'
        ];

        foreach($players as $player) {
            array_push($positionNamesOrder, 'JV');
        }

        $increment = 0;
        return view('school', [
            'positionNamesOrder' => $positionNamesOrder,
            'school' => $school,
            'players' => $players,
            'increment' => $increment
        ]);
    }


    public function createOrTie() {
        if($_POST['not_listed'] == "true") {
            return $this->create();
        } else {
            return $this->tieUserToExistingSchool();
        }
    }

    public function create() {
        $data = $_POST;
        $school = new School;

        $school['name'] = $data['school_name'];
        $school['address'] = $data['school_address'];
        $school['class'] = $data['school_class'];

        $school->saveOrFail();

        $lastInsertedId = $school->id;
        $this->tieUserToExistingSchool($lastInsertedId);

        return $this->showAddSchool();
    }

    public function tieUserToExistingSchool($id = false) {
        $user = Auth::user();

        if($id) {
            $user->school_id = $id;
        } else {
            $user->school_id = $_POST['school_id_to_tie'];
        }

        $user->saveOrFail();
        return $this->showAddSchool();
    }

}
