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
    return view('welcome');
});

// API routes 
Route::post('/api/register_user','Registrations@newRegistration');
//Route::group(['middleware' => 'checkSession'], function() {
    Route::post('/api/login','Auth@login');
    Route::post('/api/logout','Auth@logout');
//});
