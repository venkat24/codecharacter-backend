<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
// Frontend routes
Route::get('/', function () {
    return view('home');
});
Route::get('/teams', function () {
    return view('teams');
});
Route::get('/login', function () {
    return view('login');
});
Route::get('/docs', function () {
    return view('docs');
});
Route::get('/rules', function () {
    return view('rules');
});
Route::get('/submit', function () {
    return view('submit');
});
Route::get('/notifications','Notifications@showAllNotifications');
Route::get('/leaderboard','LeaderboardController@getLeaderboard');
// API routes
Route::group(['middleware' => 'setResponseHeaders'], function() {
    Route::post('api/send_invite','Registrations@sendInvite'); 
    Route::post('api/confirm_invite','Registrations@confirmInvite'); 
    Route::get('/api/check_job_status', 'SimulatorCall@checkJobStatus');
    Route::get('/api/check_if_team_exists', 'Registrations@checkIfTeamExists');
    Route::post('/api/register_user','Registrations@newRegistration');
    Route::post('/api/login','Auth@login');
});
Route::group(['middleware' => ['checkSession','setResponseHeaders']], function() {
    Route::post('/api/logout','Auth@logout');
    Route::post('/api/submit_code', 'SimulatorCall@submitCode');
});
