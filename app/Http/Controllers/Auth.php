<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Log;
use Validator;
use Exception;
use Session;
use App\Registration;
use Sangria\JSONResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Auth extends Controller
{
    /**
     * Login a registrant
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)   {
        try {
            $validator = Validator::make($request->all(),[
                'pragyanEmail' => 'required|email',
                'password'     => 'required|string'
            ]);

            if($validator->fails()) {
                $response = "Invalid Parameters";
                return JSONResponse::response(400,$response);
            }

            $pragyanEmail = $request->input('pragyanEmail');
            $password     = $request->input('password');

            $client = new Client();
            $url = env("API_BASE_URL") . "/event/login";
            $resultRaw = $client->post(($url), [
              'form_params' => [
                        'user_email'   => $pragyanEmail,
                        'user_pass'    => $password,
                        'event_id'     => env("EVENT_ID"),
                        'event_secret' => env("EVENT_SECRET"),
                      ]
            ]);

            $result = json_decode($resultRaw->getBody());

            if(is_object($result)) {
              $fullName = $result->message->user_fullname;
            } else {
              return JSONResponse::response(400, "Login Failed");
            }

            $checkForUser = Registration::where('emailid','=',$pragyanEmail)
                                        ->first();

            if($checkForUser) {
                // The user exists
                // Check if the user is already in a team
                if(isset($checkForUser->teamName)) {
                    Session::put([
                        'team_name' => $checkForUser->teamName,
                    ]);
                }
            } else {
                // Register the user
                $registrationId = Registration::insertGetId([
                    'emailId'   => $pragyanEmail,
                    'name'      => $fullName,
                ]);
                //He will not have a team at this point
            }

            Session::put([
              'user_fullname' => $fullName,
              'user_email' => $pragyanEmail,
            ]);

            return JSONResponse::response(200, "Login Success");

        } catch (Exception $e) {
            Log::error($e->getMessage()." ".$e->getLine());
            return JSONResponse::response(500, $e->getMessage());
        }
    }

    /**
     * Logout a registrant
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request) {
        try {
            $status_code = 200;
            $response = "You have been logged out";
            //Admin has logged out
            Log::info(Session::get('user_email')." logged out");
            //flush Session
            Session::flush();
            return JSONResponse::response($status_code,$response);
        } catch (Exception $e) {
            Log::error($e->getMessage()." ".$e->getLine());
            return JSONResponse::response(500, $e->getMessage());
        }
    }
}
