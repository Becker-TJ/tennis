<?php


namespace App;
use App\School;

use Illuminate\Database\Eloquent\Model;
use DB;

class Player extends Model
{
    protected $fillable = ['first_name', 'last_name', 'school_id', 'position', 'class', 'one_singles_rank', 'two_singles_rank', 'one_doubles_rank', 'two_doubles_rank'];
    protected $dates = ['created_at', 'updated_at'];

    public function getSchool()
    {
        $school = School::where('id', $this->school_id)->firstOrFail();
        return $school;
    }

}
