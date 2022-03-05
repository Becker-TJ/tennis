<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PlayerController;

class AjaxController extends Controller {
    public function savePlayerPositions(Request $request) {
        $input = $request->all();

        $playerController = new PlayerController;

        $playerController->saveRosterChanges();

        return response()->json(['success'=>'Got Simple Ajax Request.']);

    }
}
