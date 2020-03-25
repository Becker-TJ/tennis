<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Tournament extends Model
{

    protected $fillable = ['name', 'host_id', 'location_name', 'team_count', 'gender', 'address'];
    protected $dates = ['created_at', 'updated_at'];

    public function host()
    {
        return $this->belongsTo(School::class);
    }

    public function schoolAttendants() {
        return $this->hasMany(SchoolAttendant::class);
    }
}
