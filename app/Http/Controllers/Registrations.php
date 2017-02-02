<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Validator;
use Sangria\JSONResponse;

use App\Http\Controllers\Notifications;
use App\Registration;
use App\Submission;
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

    /**
     * Delete Team
     * Completely delete a team from the database
     * Wipes all of their submissions and leaderboard places
     *
     * @param teamName
     * @return \Illuminate\Http\Response
     */
    public function deleteTeam(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'teamName'    => 'required|string',
            ]);
            if($validator->fails()) {
                $message = $validator->errors()->all();
                return JSONResponse::response(400, $message);
            }
            $leaderEmail = $request->input('leaderEmail');
            $leaderRegId = Registration::where('emailId','=',$leaderEmail)
                                       ->pluck('id');

            $teamName = $request->input('teamName');
            $teamInfo = Team::where('teamName','=',$teamName)
                                 ->first();

            if($teamInfo) {
                Registration::where('teamName','=',$teamName)
                            ->update([
                                'teamName' => '',
                            ]);

                Invites::where('fromTeamId','=',$teamInfo->id)
                       ->delete();

                Notifications::where('team_name','=',$teamName)
                             ->delete();

                Submission::where('teamId','=',$teamInfo->id)
                          ->delete();

                Leaderboard::where('teamId','=',$teamInfo->id)
                          ->delete();

                Team::where('id','=',$teamId)
                    ->delete();

                return JSONResponse::response(200,"Team Deleted");
            } else {
                return JSONResponse::response(400,"Team Not Found");
            }
        } catch (Exception $e) {
            Log::error($e->getMessage()." ".$e->getLine());
            return JSONResponse::response(500, $e->getMessage());
        }
    }

    /**
     * Delete Member
     * Remove a particular member from a team
     * DOES NOT WORK FOR TEAM LEADER
     * To delete the leader, use the deleteLeader function instead
     *
     * @param teamName
     * @return \Illuminate\Http\Response
     */
    public function deleteMember(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'teamName'    => 'required|string',
                'userEmail'   => 'required|string',
            ]);
            if($validator->fails()) {
                $message = $validator->errors()->all();
                return JSONResponse::response(400, $message);
            }
            $teamName = $request->input('teamName');
            $teamInfo = Team::where('teamName','=',$teamName)
                            ->first();

            $userEmail = $request->input('userEmail');

            Registration::where('emailId','=',$userEmail)
                        ->update([
                            'teamName' => '',
                        ]);

        } catch (Exception $e) {
            Log::error($e->getMessage()." ".$e->getLine());
            return JSONResponse::response(500, $e->getMessage());
        }
    }
    /**
     * Function to return the team members in a team, given the team name
     *
     * @param teamName
     * @return Illuminate/Http/Response
     */
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

            if(!$teamId)
            {
                return JSONResponse::response(400, "TEAM DOESN'T EXIST");
            }

            $teamMembers = Invite::where('fromTeamId','=',$teamId)
                                  ->join('registrations','invites.toRegistrationId','=','registrations.id')
                                  ->get(['registrations.name','registrations.emailId','registrations.id','invites.status'])
                                  ->toArray();

            $teamLeader = Team::where('teams.teamName', '=', $teamName)
                                ->join('registrations', 'teams.leaderRegistrationId', '=','registrations.id')
                                ->get(['registrations.name','registrations.emailId','registrations.id'])
                                ->first();
            $teamLeader["status"] = "LEADER";
            array_push($teamMembers, $teamLeader);
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
                                             ->where('teamName', '=', '')
                                             ->pluck('id');
            if(!$toRegistrationId)
            {
                return JSONResponse::response(400, "Member of a team");
            }

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
              <button class='button' onclick='acceptInvite(event)' id='$teamName'>
                Accept Invitation
              </button>
            ";
            $message_type = "INVITE";
            Notifications::sendNotification($toRegistrationId,$title,$message,$message_type);
            $participantName = Registration::where('emailId','=',$email)
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
            $userEmail = $request->input('userEmail');

            $fromTeam = Team::where('teamName','=',$teamName)
                            ->first();

            $leaderEmail = Registration::where('id','=',$fromTeam->id)
                                       ->first();

            $toRegistrationId = Registration::where('emailId','=',$userEmail)
                                             ->where('teamName', '=', '')
                                             ->pluck('id');

            if(!$toRegistrationId) {
                return JSONResponse::response(400, "Already a member");
            }

            // Match aginst the last invite that was sent to this user
            // from the particular teamName
            $invite = Invite::where('fromTeamId','=',$fromTeam->id)
                               ->where('toRegistrationId','=',$toRegistrationId)
                               ->orderBy('id','desc')
                               ->first();

            $invite->status = 'ACCEPTED';
            $invite->save();

            $teamResponse = Registration::where('emailId','=',$userEmail)
                                         ->update([
                                            'teamName' => $teamName
                                            ]);

            //Delete the invites a person has gotten
            Notification::where('userId','=',$toRegistrationId)
                        ->where('message_type','=','INVITE')
                        ->delete();

            Invite::where('toRegistrationId','=',$toRegistrationId)
                  ->delete();

            $response = JSONResponse::response(200, 'Invite Confirmed');
            return $response;

        } catch (Exception $e) {
            Log::error($e->getMessage()." ".$e->getLine());
            return JSONResponse::response(500, $e->getMessage());
        }
    }
}
