<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchoolAttendant extends Model
{
    protected $hidden = ['school_id', 'tournament_id'];
    protected $dates = ['created_at', 'updated_at'];

//    public function tournament(){
//        return $this->belongsTo(Tournament::class);
//    }
//
//    public function school(){
//        return $this->belongsTo(School::class);
//    }


}
