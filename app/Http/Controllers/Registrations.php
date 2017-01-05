<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Validator;
use Sangria\JSONResponse;

use App\Registration;

class Registrations extends Controller
{
    /**
     * Add a new participant to Code Character
     * Takes all the registration parameters and inserts into the 
     * registrations database without a foreign key check to teams
     *
     * @param pragyanId
     * @param name
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
            ]);

            if($validator->fails()) {
                $message = $validator->errors()->all();
                return JSONResponse::response(400, $message);
            }
            
            $salt = getenv('PASSWORD_SECRET');
            $password = sha1($request->input('password').$salt); 
            $response = Registration::insert([
                                        'pragyanId' => $request->input('pragyanId'),
                                        'name'      => $request->input('name'),
                                        'emailId'   => $request->input('emailId'),
                                        'phoneNo'   => $request->input('phoneNo'),
                                        'password'  => $password,
                                      ]);
            return JSONResponse::response(200,"Registration complete");
        } catch (Exception $e) {
            Log::error($e->getMessage()." ".$e->getLine());
            return JSONResponse::response(500, $e->getMessage());
        }
    }
}

