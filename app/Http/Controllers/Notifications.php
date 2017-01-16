<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Validator;
use Sangria\JSONResponse;

use App\Registration;
use App\Notification;
use App\Invite;
use App\Team;

class Notifications extends Controller
{
    /**
     * Display all the notifications a user has received
     * This includes team and subission notifications
     * This DOES NOT return JSON. This is for directly injecting
     * into blade templates. The Controller returns a view.
     *
     * @param emailId
     * @return \Illuminate\Http\Response
     */
    public function showAllNotifications(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                //'emailId'   => 'required',
            ]);

            if($validator->fails()) {
                $message = $validator->errors()->all();
                return $message;
            } 
            //$emailId = $request->input('emailId');
            $emailId = 'venkat@venkat.com';
            $userId = Registration::where('emailId','=',$emailId)
                                  ->first()
                                  ->pluck('id');

            $notifications = Notification::where('userId','=',$userId)
                                         ->get();

            return view('notifications',[
                'notifications' => $notifications,
            ]);

        } catch (Exception $e) {
            Log::error($e->getMessage()." ".$e->getLine());
            return $e->getMessage();
        }
    }
}
