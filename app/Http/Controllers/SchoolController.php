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

        return $this->showAddSchoolView();
    }

    public function tieUserToExistingSchool($id = false) {
        $user = Auth::user();

        if($id) {
            $user->school_id = $id;
        } else {
            $user->school_id = $_POST['school_id_to_tie'];
        }

        $user->saveOrFail();
        return $this->showAddSchoolView();
    }

}
