<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Player;

class School extends Model
{
    protected $fillable = ['name', 'address', 'class'];
    protected $dates = ['created_at', 'updated_at'];

}
