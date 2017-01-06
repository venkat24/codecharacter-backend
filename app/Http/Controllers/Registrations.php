<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Validator;
use Sangria\JSONResponse;

use App\Registration;
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
}

