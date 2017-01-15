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

class Notifications extends Controller
{
    /**
     * Display all the notifications a user has received
     * This includes team and subission notifications
     *
     * @param emailId
     * @return \Illuminate\Http\Response
     */
    public function showAllNotifications(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'emailId'   => 'required',
            ]);

            if($validator->fails()) {
                $message = $validator->errors()->all();
                return JSONResponse::response(400, $message);
            } 

            return JSONResponse::response(200,"Registration complete");
        } catch (Exception $e) {
            Log::error($e->getMessage()." ".$e->getLine());
            return JSONResponse::response(500, $e->getMessage());
        }
    }
}
