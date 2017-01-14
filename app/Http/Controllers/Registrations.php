<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Validator;
use Sangria\JSONResponse;

use App\Registration;
use App\Invite;
use App\Team;

class Registrations extends Controller
{
    /**
     * Add a new participant to Code Character
     * Takes all the registration parameters and inserts into the
     * registrations database without a foreign key check to teams
     *
     * @param pragyanId
     * @param password
     * @param name
     * @param teamName
     * @param emailId
     * @param phoneNo
     * @return \Illuminate\Http\Response
     */
    public function newRegistration(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'pragyanId' => 'required|integer',
                'password'  => 'required',
                'name'      => 'required',
                'emailId'   => 'required',
                'phoneNo'   => 'required|integer|digits:10',
                'teamName'  => 'required',
            ]);

            if($validator->fails()) {
                $message = $validator->errors()->all();
                return JSONResponse::response(400, $message);
            }

            $salt = getenv('PASSWORD_SECRET');
            $password = sha1($request->input('password').$salt);
            $registrationId = Registration::insertGetId([
                'pragyanId' => $request->input('pragyanId'),
                'emailId'   => $request->input('emailId'),
                'teamName'  => $request->input('teamName'),
                'phoneNo'   => $request->input('phoneNo'),
                'name'      => $request->input('name'),
                'password'  => $password,
            ]);
            // Team name insertion
            $teamName = $request->input('teamName');
            $checkForTeamName = Team::where('teamName','=',$teamName)
                ->get();

            // Create a new team if the user is the team leader
            // (registering as the first member)
            if($checkForTeamName->isEmpty()) {
                $teamCreationResponse = Team::insert([
                    'teamName'             => $teamName,
                    'leaderRegistrationId' => $registrationId,
                    'currentLevel'         => 0,
                    'score'                => 0,
                ]);
            }

            return JSONResponse::response(200,"Registration complete");
        } catch (Exception $e) {
            Log::error($e->getMessage()." ".$e->getLine());
            return JSONResponse::response(500, $e->getMessage());
        }
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
                              ->first()
                              ->pluck('id');

            $toRegistrationId = Registration::where('emailId','=',$email)
                                             ->first()
                                             ->pluck('id');

            $insertResponse = Invite::insert([
                'toRegistrationId' => $toRegistrationId,
                'fromTeamId'       => $fromTeamId,
            ]);
            $response = JSONResponse::response(200, 'Invite Sent');
            return $response;

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
