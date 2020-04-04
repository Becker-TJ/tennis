<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Tournament extends Model
{

    protected $fillable = ['name', 'host_id', 'location_name', 'team_count', 'gender', 'address', 'level', 'privacy_setting', 'date', 'time'];
    protected $dates = ['created_at', 'updated_at'];

}
