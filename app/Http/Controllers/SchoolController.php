<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Routing\Controller;
use App\School;

class SchoolController extends Controller
{
    public function __construct()
    {

    }

    public function showAddSchoolView() {
        $schools = School::all();

        return view('addschool', [
            'schools' => $schools
        ]);
    }

    public function createOrTie() {
        if($_POST['not_listed'] == "true") {
            $this->create();
        } else {
            $this->tieUserToExistingSchool();
        }
    }

    public function create() {
        $data = $_POST;
        $schools = new School;
        $user = Auth::user();

        $schools['name'] = $data['school_name'];
        $schools['address'] = $data['school_address'];
        $schools['class'] = $data['school_class'];

        $schools->saveOrFail();
        $lastInsertedId = $schools->id;
        $this->tieUserToExistingSchool($lastInsertedId);
    }

    public function tieUserToExistingSchool($id = false) {
        $user = Auth::user();

        if($id) {
            $user->school_id = $id;
        } else {
            $user->school_id = $_POST['school_id_to_tie'];
        }

//        $user->saveOrFail();
//        $yeah = 0;
//        $yo = 4;
//        $teej = 6;

        $this->showAddSchoolView();

    }

}
