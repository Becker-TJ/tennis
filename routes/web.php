<?php

use App\Http\Controllers\AjaxController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\TournamentController;
use app\Http\Controllers;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------+------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/home', function () {
    return view('home');
});
Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', function () {
    return view('login');
});
Route::get('/register', function () {
    return view('register');
});
Route::get('/verify', function () {
    return view('verify');
});

Route::get('/createtournament', [TournamentController::class, 'showCreateTournament']);
Route::get('/createtournament/{tournament}', [TournamentController::class, 'showEditTournament']);
Route::post('/createtournament', [TournamentController::class, 'create']);
Route::post('/createtournament/{tournament}', [TournamentController::class, 'edit']);

Route::get('/tournaments', [TournamentController::class, 'showTournaments']);
Route::get('/tournament/{tournament}', [TournamentController::class, 'showTournament'])->name('tournament');

Route::get('/addschool', [SchoolController::class, 'showAddSchool']);
Route::post('/addschool', [SchoolController::class, 'createOrTie']);

Route::get('/players', [PlayerController::class, 'showAllPlayers']);
Route::post('/players', [PlayerController::class, 'showFilteredPlayers']);

Route::get('schools', [SchoolController::class, 'showSchools']);
Route::get('/school/{school}', [SchoolController::class, 'showSchool'])->name('school');
Route::post('/school/addnewplayer', [PlayerController::class, 'create']);

Route::get('/message', function () {
    return view('message');
});
Route::post('/getmsg', [AjaxController::class, 'index']);

Route::post('/savePlayerPositions', [AjaxController::class, 'savePlayerPositions']);
Route::post('/inviteSchools', [AjaxController::class, 'inviteSchools']);
Route::post('/getBracketData', [AjaxController::class, 'getBracketData']);
Route::post('/saveScore', [AjaxController::class, 'saveScore']);

Route::get('/bracket', [TournamentController::class, 'showBracket']);

//Route::get('/posts/{post}', function ($post) {
//    $posts = [
//        'my-first-post' => 'Hello, this is my first blog post!',
//        'my-second-post' => 'Now I am getting the hang of this blogging thing.'
//    ];
//
//    if(! array_key_exists($post, $posts)) {
//        abort(404, 'Sorry that post was not found.');
//    }
//
//    return view('post', [
//        'post' => $post[$post]
//    ]);
//});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
