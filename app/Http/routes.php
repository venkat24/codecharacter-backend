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
Route::get('/teams', 'TeamController@teamPage');
Route::get('/login', function () {
    if(Session::get('user_email')) {
        return view('home');
    } else {
        return view('login');
    }
});
Route::get('/docs', function () {
    return view('docs');
});
Route::get('/rules', function () {
    return view('rules');
});
Route::get('/submit','SimulatorCall@getSubmitPage');
Route::get('/notifications','Notifications@showAllNotifications');
Route::get('/leaderboard','LeaderboardController@getLeaderboard');
// API routes
Route::group(['middleware' => 'setResponseHeaders'], function() {
    Route::post('/api/login','Auth@login');
});
Route::group(['middleware' => ['checkSession','setResponseHeaders']], function() {
    Route::post('/api/logout','Auth@logout');
    Route::post('/api/submit_code', 'SimulatorCall@submitCode');
    Route::post('/api/leave_team','Registrations@leaveTeam');
    Route::post('/api/change_leader','Registrations@changeLeader');
    Route::post('/api/delete_team','Registrations@deleteTeam');
    Route::post('/api/delete_member','Registrations@deleteMember');
    Route::get('/api/check_if_team_exists', 'Registrations@checkIfTeamExists');
    Route::post('/api/create_team','Registrations@createTeam');
    Route::get('/api/get_team_members','Registrations@getTeamMembers');
    Route::post('/api/send_invite','Registrations@sendInvite'); 
    Route::post('/api/confirm_invite','Registrations@confirmInvite'); 
    Route::post('/api/cancel_invite','Registrations@cancelInvite');
    Route::get('/api/check_job_status', 'SimulatorCall@checkJobStatus');
});
