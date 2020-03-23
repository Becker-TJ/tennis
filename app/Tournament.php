<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    protected $fillable = ['name', 'host', 'location_name', 'total_teams', 'gender', 'address'];
    protected $dates = ['created_at', 'updated_at'];

    public function host()
    {
        return $this->belongsTo(School::class);
    }

    //public function participants() {
        //return $this->hasMany()
    //}

    public function schoolAttendants() {
        return $this->hasMany(SchoolAttendant::class);
    }
}
