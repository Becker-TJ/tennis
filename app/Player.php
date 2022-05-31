<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\School;
use DB;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'school_id',
        'position',
        'class',
        'boys_one_singles_rank',
        'boys_two_singles_rank',
        'girls_one_singles_rank',
        'girls_two_singles_rank',
    ];
    public function getSchool()
    {
        $school = School::where('id', $this->school_id)->firstOrFail();

        return $school;
    }
}
