<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = ['first_name', 'last_name', 'school_id', 'position'];
    protected $dates = ['created_at', 'updated_at'];

    public function school()
    {
        return $this->belongsTo('App\School');
    }

}
