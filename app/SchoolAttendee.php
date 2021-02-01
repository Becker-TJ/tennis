<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchoolAttendee extends Model
{
    protected $hidden = ['school_id', 'tournament_id'];
    protected $dates = ['created_at', 'updated_at'];

}
