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
                'pragyanId' => 'required|integer',
                'password'  => 'required'
            ]);

            if($validator->fails()) {
                $response = "Invalid Parameters";
                return JSONResponse::response(400,$response);
            }

            $pragyanId = $request->input('pragyanId');
            $password = $request->input('password');
            $user = Registration::where('pragyanId', '=', $pragyanId)
                                ->first();

            $salt = getenv('PASSWORD_SECRET');
            
            if($user)  {
                if(sha1($password.$salt) === $user->password)    {
                    //set session                                 
                    Session::put(['pragyanId' => $user->pragyanId]);                                        
                    Log::info($pragyanId . " has logged in"); 
                    return JSONResponse::response(200, "Success");
                }
                else    {
                    //return 401 for unauthorized access
                    $status_code = 401;
                    $response = "Incorrect password";
                }
            }   
            else    {
                //return 401 for unauthorized access
                $status_code = 401;
                $response = "Not a registered user";
            }     

            //the return statement for all errors
            return JSONResponse::response($status_code, $response);             
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
            Log::info(Session::get('pragyanId')." logged out");
            //flush Session
            Session::flush();
            return JSONResponse::response($status_code,$response);
        } catch (Exception $e) {
            Log::error($e->getMessage()." ".$e->getLine());
            return JSONResponse::response(500, $e->getMessage());
        }
    }
}
