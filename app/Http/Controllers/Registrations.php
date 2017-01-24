<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Validator;
use Sangria\JSONResponse;

use App\Http\Controllers\Notifications;
use App\Registration;
use App\Invite;
use App\Team;

class Registrations extends Controller
{
    /**
     * Create Team
     *
     * @param teamName
     * @param leaderEmail
     * @return \Illuminate\Http\Response
     */
    public function createTeam(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'teamName'    => 'required|string',
                'leaderEmail' => 'required|string',
            ]);
            if($validator->fails()) {
                $message = $validator->errors()->all();
                return JSONResponse::response(400, $message);
            }
            $leaderEmail = $request->input('leaderEmail');
            $leaderRegId = Registration::where('emailId','=',$leaderEmail)
                                       ->pluck('id');

            $teamName = $request->input('teamName');
            $teamNameCheck = Team::where('teamName','=',$teamName)
                                 ->first();

            Session::put([
              'team_name' => $teamName
            ]);

            if($teamNameCheck) {
                // Team Alreafy Exists
                return JSONResponse::response(400,"Team Name already in use");
            } else {
                // New Team Creation
                $teamInsert = Team::insert([
                    'teamName'             => $teamName,
                    'leaderRegistrationId' => $leaderRegId,
                    'currentLevel'         => 0,
                    'score'                => 0,
                  ]);
                $userInsert = Registration::where('id','=',$leaderRegId)
                  ->update([
                    'teamName' => $teamName,
                  ]);
                return JSONResponse::response(200,"Team Created");
            }

        } catch (Exception $e) {
            Log::error($e->getMessage()." ".$e->getLine());
            return JSONResponse::response(500, $e->getMessage());
        }
    }
    public function getTeamMembers(Request $request) {
            $validator = Validator::make($request->all(), [
                'teamName'    => 'required|string',
            ]);
            if($validator->fails()) {
                $message = $validator->errors()->all();
                return JSONResponse::response(400, $message);
            }
            $teamName = $request->input('teamName');

            $teamId = Team::where('teamName','=',$teamName)->pluck('id');
            
            $teamMembers = Invite::where('fromTeamId','=',$teamId)
                                  ->join('registrations','invites.toRegistrationId','=','registrations.id')
                                  ->get(['registrations.name','registrations.emailId','registrations.id','invites.status']);

            return JSONResponse::response(200, $teamMembers);
    }

    /**
     * Function to check if a team name is already taken.
     * This should be called in from text field as AJAX for both new team
     * creation and existing team joining
     *
     * Returns "EXISTS" or "DOES NOT EXIST"
     *
     * @param teamName
     * @return \Illuminate\Http\Response
     */
    public function checkIfTeamExists(Request $request) {

        $validator = Validator::make($request->all(), [
            'teamName'  => 'required',
        ]);
        if($validator->fails()) {
            $message = $validator->errors()->all();
            return JSONResponse::response(400, $message);
        }
        $response = Team::where('teamName','=',$request->input('teamName'))
            ->get();
        if($response->isEmpty()) {
            return JSONResponse::response(200, "DOES NOT EXIST");
        }
        return JSONResponse::response(200, "EXISTS");
    }

    /**
     * Send Invite
     * Sends invite to the specified email address
     * This will also ad an invite notification to the
     * receiver of the invite
     * Returns the name of the receiving participant as the response;
     *
     * @param teamName
     * @param email
     * @return \Illuminate\Http\Response
     */
    public function sendInvite(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'teamName'  => 'required',
                'email'     => 'required',
            ]);
            if($validator->fails()) {
                $message = $validator->errors()->all();
                return JSONResponse::response(400, $message);
            }

            /**
             * fromTeamId must be queried from teamName, and
             * toRegistrationId must be queried from email
             */

            $teamName = $request->input('teamName');
            $email    = $request->input('email');


            $fromTeamId = Team::where('teamName','=',$teamName)
                              ->pluck('id');

            $toRegistrationId = Registration::where('emailId','=',$email)
                                             ->first()
                                             ->pluck('id');

            $insertResponse = Invite::insert([
                'toRegistrationId' => $toRegistrationId,
                'fromTeamId'       => $fromTeamId,
            ]);

            /**
             * Send the invite notitfication to the receiver, with a 
             * link to to the confirmation link, with $message and $title
             */
            $title = "Invitation to Join Team $teamName";
            $message = "
              <br />
              You have been invited to join team $teamName. 
              <br />
              Click the following link to accept the invitation : 
              <button class='button' onclick='acceptInvite()'>
              <button class='button'>
                Accept Invitation
              </button>
            ";
            Notifications::sendNotification($toRegistrationId,$title,$message);

            $participantName = Registration::where('emailId','=',$email)
                                             ->first()
                                             ->pluck('name');

            return JSONResponse::response(200, $participantName);

        } catch (Exception $e) {
            Log::error($e->getMessage()." ".$e->getLine());
            return JSONResponse::response(500, $e->getMessage());
        }
    }

    /**
     * Confirm Invite
     * Confirm invite from the specified email address
     *
     * @param teamName
     * @param LeaderEmail
     * @param UserEmail
     * @return \Illuminate\Http\Response
     */
    public function confirmInvite(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'teamName'        => 'required',
                'leaderEmail'     => 'required',
                'userEmail'       => 'required',
            ]);
            if($validator->fails()) {
                $message = $validator->errors()->all();
                return JSONResponse::response(400, $message);
            }

            /**
             * fromTeamId must be queried from teamName, and
             * toRegistrationId must be queried from email
             */

            $teamName = $request->input('teamName');
            $LeaderEmail    = $request->input('leaderEmail');
            $UserEmail      = $request->input('UserEmail');

            $fromTeamId = Team::where('teamName','=',$teamName)
                              ->first()
                              ->pluck('id');

            $toRegistrationId = Registration::where('emailId','=',$email)
                                             ->pluck('id')
                                             ->first();

            // Match aginst the last invite that was sent to this user
            // from the particular teamName
            $inviteId = Invite::where('fromTeamId','=',$fromTeamId)
                               ->where('toRegistrationId','=',$toRegistrationId)
                               ->order_by('id','desc')
                               ->first()
                               ->pluck('id');

            $inviteResponse = Invite::where('id','=',$inviteId)
                                    ->update([
                'status' => 'CONFIRMED'
            ]);

            $teamResponse = Registrations::where('emailId','=',$userEmail)
                                         ->update([
                    'teamName' => $teamName
            ]);

            $response = JSONResponse::response(200, 'Invite Confirmed');

        } catch (Exception $e) {
            Log::error($e->getMessage()." ".$e->getLine());
            return JSONResponse::response(500, $e->getMessage());
        }
    }
}
