<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

class player extends Model
{
    protected $fillable = ['first_name', 'last_name'];
    protected $hidden = ['school_id'];
    protected $dates = ['created_at', 'updated_at'];

    public function school() {
        return $this->hasOne(School::class);
    }
}
