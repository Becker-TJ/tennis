<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchoolAttendee extends Model
{
    protected $fillable = ['school_id', 'tournament_id', 'invite_accepted'];
    protected $dates = ['created_at', 'updated_at'];

    public function getSchool()
    {
        $school = School::where('id', $this->school_id)->first();
        return $school;
    }
}
