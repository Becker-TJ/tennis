<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    protected $fillable = ['name', 'host', 'location', 'total_teams', 'gender'];

    public function host()
    {
        return $this->hasOne(School::class);
    }

    //public function participants() {
        //return $this->hasMany()
    //}
}
