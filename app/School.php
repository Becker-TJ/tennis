<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = ['name', 'city'];
    protected $dates = ['created_at', 'updated_at'];

    public function players() {
        return $this->hasMany(Player::class);
    }
}
