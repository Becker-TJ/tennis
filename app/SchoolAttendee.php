<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchoolAttendee extends Model
{
    protected $hidden = ['school_id', 'tournament_id'];
    protected $dates = ['created_at', 'updated_at'];

    public function getSchool()
    {
        $school = School::where('id', $this->school_id)->first();
        return $school;
    }
}
