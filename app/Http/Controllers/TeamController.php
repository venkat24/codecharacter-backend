<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Session;
use App\Team;
use App\Registration;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class TeamController extends Controller
{
    public function teamPage(Request $request) {
        if(Session::get('user_email')) {
            $userEmail = Session::get('user_email');
            $user = Registration::where('emailId','=',$userEmail)
                                ->first();

            $leaderCheck = Team::where('leaderRegistrationId','=',$user->id)
                               ->get();

            if($leaderCheck->count()) {
                return view('teams');
            } else {
                if(Session::get('team_name')) {
                    return view('member'); 
                } else {
                    return view('teams');
                }
            }
        } else {
            return view('login');
        }
    }
}
