<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Redirect;
use Session;
use Validator;
use Sangria\JSONResponse;

use App\Registration;
use App\Notification;
use App\Invite;
use App\Team;

class Notifications extends Controller
{
    /**
     * Utility function for internal use, not a Controller
     * Adds a new notification given the title, message, and
     * userId to send the Notification to.
     *
     * @param userId
     * @param title
     * @param message
     */
    public static function sendNotification($userId, $title, $message, $message_type, $teamName) {
        $insert_response = Notification::insert([
            'title'        => $title,
            'userId'       => $userId,
            'message'      => $message,
            'messageType'  => $message_type,
            'teamName'     => $teamName,
        ]);
    }
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
            $username = Session::get('user_fullname');
            $loginUrl = '/login';
            if(!$username) {
                return Redirect::away($loginUrl);
            } else {
                $emailId = Session::get('user_email');
                $user = Registration::where('emailId','=',$emailId)
                                    ->first();

                $notifications = Notification::where('userId','=',$user->id)
                                             ->orderBy('id','desc')
                                             ->get();

                return view('notifications',[
                    'notifications' => $notifications,
                ]);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage()." ".$e->getLine());
            return $e->getMessage();
        }
    }
}
