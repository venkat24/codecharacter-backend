<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Log;
use App\Team;
use App\Leaderboard;
use Sangria\JSONResponse;

class LeaderboardController extends Controller
{
    /**
     * FrontEnd Blade route to render the current Leaderboard
     * Returns the raw collection containing the current leaders,
     * their levels, and their scores.
     *
     * Does not take any parameters, the leaderboard is common
     *
     * @return view
     */
    public function getLeaderboard(Request $request) {
        try {
          $leaderboard = Team::where('currentLevel','>','0')
                             ->orderBy('currentLevel','desc')
                             ->orderBy('score','desc')
                             ->select('score','currentLevel','teamName')
                             ->get();
            return view('leaderboard',[
              'leaderboard' => $leaderboard
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage()." ".$e->getLine());
            return $e->getMessage();
        }
    }
}

