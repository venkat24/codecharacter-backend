<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Log;
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
          $leaderboard = Leaderboard::join('teams','teams.id','=','leaderboard.teamId')
                                      ->orderBy('leaderboard.level','desc')
                                      ->orderBy('leaderboard.score','desc')
                                      ->select('leaderboard.score','leaderboard.level','teams.teamName')
                                      ->take(5)
                                      ->get();

            //return view('leaderboard',[
            return view('leaderboard-dummy',[
              'leaderboard' => $leaderboard
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage()." ".$e->getLine());
            return $e->getMessage();
        }
    }
}

