<?php

namespace App\Http\Controllers;
use App\Tournament;

use Illuminate\Http\Request;

class TournamentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function create(array $data)
    {
        return Tournament::create([
            'name' => $data['name'],
            'location_name' => $data['location_name'],
            'total_teams' => $data['total_teams'],
            'gender' => $data['gender'],
            'address' => $data['address']
        ]);
    }
}
