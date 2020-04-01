<?php

use Illuminate\Support\Facades\Route;
use app\Http\Controllers;

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

Route::get('/createtournament', 'TournamentController@showCreateTournament');
Route::post('/createtournament', 'TournamentController@create');

Route::get('/tournaments', 'TournamentController@showTournaments');

Route::get('/addschool', 'SchoolController@showAddSchool');
Route::post('/addschool', 'SchoolController@createOrTie');
Route::get('/players', 'PlayerController@showPlayers');
Route::get('schools', 'SchoolController@showSchools');
Route::get('/school/{school}', 'SchoolController@showRoster');
Route::post('/school/{school}','PlayerController@saveVarsityOrder');
Route::get('/message',function() {
    return view('message');
});
Route::post('/getmsg','AjaxController@index');

Route::post('/savePlayerPositions','AjaxController@savePlayerPositions');





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

Route::get('/home', 'HomeController@index')->name('home');
