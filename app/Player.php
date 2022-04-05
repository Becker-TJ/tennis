<?php


namespace App;
use App\School;

use Illuminate\Database\Eloquent\Model;
use DB;

class Player extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'school_id',
        'position',
        'class',
        'boys_one_singles_rank',
        'boys_two_singles_rank',
        'boys_one_doubles_rank',
        'boys_two_doubles_rank',
        'girls_one_singles_rank',
        'girls_two_singles_rank',
        'girls_one_doubles_rank',
        'girls_two_doubles_rank'
    ];
    protected $dates = ['created_at', 'updated_at'];

    public function getSchool()
    {
        $school = School::where('id', $this->school_id)->firstOrFail();
        return $school;
    }


}
