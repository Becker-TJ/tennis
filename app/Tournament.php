<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\DoublesTeam;


class Tournament extends Model
{

    protected $fillable = ['name', 'host_id', 'location_name', 'team_count', 'gender', 'address', 'level', 'privacy_setting', 'date', 'time'];
    protected $dates = ['created_at', 'updated_at'];

    public function getHost()
    {
        $school = School::where('id', $this->host_id)->first();
        return $school;
    }

    public function getGirlsOneDoublesSortedByRank() {
        $attendees = SchoolAttendee::all()->where('tournament_id', '=', $this->id);

        foreach($attendees as $attendee) {
            $school = $attendee->getSchool();
            $oneDoublesTeam = $school->getGirlsOneDoublesTeam();
            $doublesTeams[$attendee->school_id] = $oneDoublesTeam;
        }

        $stillSorting = true;
        $ranks = [];
        $doublesTeamClass = new DoublesTeam;

        foreach($doublesTeams as &$team) {

            $playerOne = $team[0];
            $playerTwo = $team[1];
            $existingDoublesTeam = $doublesTeamClass->findDoublesTeam($playerOne->id, $playerTwo->id);

            if($existingDoublesTeam) {
                $team['rank'] = $existingDoublesTeam->girls_one_doubles_rank;
                $ranks[] = $existingDoublesTeam->girls_one_doubles_rank;
                $team['id'] = $existingDoublesTeam->id;
            } else {
                $team['rank'] = 99999;
                $ranks[] = 99999;
            }
        }

        unset($team);

        sort($ranks, SORT_NUMERIC);
        $increment = 0;
        $sortedOneDoubles = [];
        do {
            foreach($doublesTeams as $team) {
                if((count($doublesTeams) > $increment) && ($team['rank'] === $ranks[$increment])) {
                    $sortedOneDoubles[$team['id']] = $team;
                    $increment++;
                }
            }

            if(count($sortedOneDoubles) === count($doublesTeams)) {
                $stillSorting = false;
            }

        } while ($stillSorting);

        return $sortedOneDoubles;
    }



}
