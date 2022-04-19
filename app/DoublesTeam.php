<?php



namespace App;


use Illuminate\Database\Eloquent\Model;
use DB;

class DoublesTeam extends Model
{
    protected $fillable = ['player_1', 'player_2'];
    protected $dates = ['created_at', 'updated_at'];

    public function saveDoublesTeam() {
        $teamAlreadyExists = false;

        $playerOneID = $this->player_1;
        $playerTwoID = $this->player_2;

        $doublesTeamsWithPlayerOneInPlayerOneColumn = DoublesTeam::all()->where('player_1', $playerOneID);
        $doublesTeamsWithPlayerOneInPlayerTwoColumn = DoublesTeam::all()->where('player_2', $playerOneID);

        foreach($doublesTeamsWithPlayerOneInPlayerOneColumn as $doublesTeam) {
            if($doublesTeam->player_2 === $playerTwoID) {
                $teamAlreadyExists = true;
            }
        }

        foreach($doublesTeamsWithPlayerOneInPlayerTwoColumn as $doublesTeam) {
            if($doublesTeam->player_1 === $playerTwoID) {
                $teamAlreadyExists = true;
            }
        }

        if(!$teamAlreadyExists) {
            $this->saveOrFail();
            return true;
        } else {
            return false;
        }

    }

    public function findDoublesTeam($playerOneID, $playerTwoID) {
        $teamAlreadyExists = false;

        $doublesTeamsWithPlayerOneInPlayerOneColumn = DoublesTeam::all()->where('player_1', $playerOneID);
        $doublesTeamsWithPlayerOneInPlayerTwoColumn = DoublesTeam::all()->where('player_2', $playerOneID);

        foreach($doublesTeamsWithPlayerOneInPlayerOneColumn as $doublesTeam) {
            if($doublesTeam->player_2 === $playerTwoID) {
                $teamAlreadyExists = true;
                $oneDoublesTeam = $doublesTeam;
            }
        }

        foreach($doublesTeamsWithPlayerOneInPlayerTwoColumn as $doublesTeam) {
            if($doublesTeam->player_1 === $playerTwoID) {
                $teamAlreadyExists = true;
                $oneDoublesTeam = $doublesTeam;
            }
        }

        if($teamAlreadyExists) {
            return $oneDoublesTeam;
        } else {
            return false;
        }
    }

}
