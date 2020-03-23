<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
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
Route::get('/createtournament', function () {
    return view('createtournament');
});
Route::post('CreateTournament', 'TournamentController@create');
//Route::post('createtournament'), 'TournamentController@createTournament');



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
