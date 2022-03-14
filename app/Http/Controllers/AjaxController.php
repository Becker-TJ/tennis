<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PlayerController;
use App\SchoolAttendee;

class AjaxController extends Controller {
    public function savePlayerPositions(Request $request) {
        $input = $request->all();
        $updatedPositionOrder = $input['updatedPositionOrder'];
        $playerController = new PlayerController;
        $newPosition = 1;

        foreach($updatedPositionOrder as $player) {
            $playerID = intval($player[1]);
            $playerController->savePositionChanges($playerID, $newPosition);
            $newPosition++;
        }

        return response()->json(['success'=>'Saved position changes.']);

    }

    public function inviteSchools(Request $request) {
        $input = $request->all();
        $schoolInviteeIDs = $input['schoolInviteeIDs'];
        $tournamentID = intval($input['tournamentID']);

        foreach($schoolInviteeIDs as $inviteeID) {
            $inviteeID = intval($inviteeID);
            $schoolAttendee = new SchoolAttendee;
            $schoolAttendee->school_id = $inviteeID;
            $schoolAttendee->tournament_id = $tournamentID;
            $schoolAttendee->saveOrFail();
        }

        return response()->json(['success'=>'Invites sent.']);
    }


}
