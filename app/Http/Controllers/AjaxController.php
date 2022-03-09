<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PlayerController;

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
}
