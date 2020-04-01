<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Tournament extends Model
{

    protected $fillable = ['name', 'host_id', 'location_name', 'team_count', 'gender', 'address', 'level'];
    protected $dates = ['created_at', 'updated_at'];

}
