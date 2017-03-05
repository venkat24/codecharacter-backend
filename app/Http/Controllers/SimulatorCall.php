<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Jobs\SimulatorProcess;
use Sangria\JSONResponse;
use Validator;
use Redirect;

use Carbon\Carbon;
use Session;
use App\Job;
use App\Submissions;
use App\Team;

class SimulatorCall extends Controller
{
    public function getSubmitPage(Request $request){
        if(Session::get('user_email')) {
            if (Session::get('team_name')) {
                $teamName = Session::get('team_name');
                $level = Team::where('teamName','=',$teamName)
                         ->first();
                return view('submit-dummy',[
                    'level' => $level
                ]);
            } else {
                return Redirect::to('/teams');
            }
        } else {
            return Redirect::to('/login');
        }
    }
    public function callsimulator($fileName)
    {
        $this->dispatch(new SimulatorProcess($fileName));
    }

    /**
     * Returns the job status of the current team's task
     * 200 status_code if there is no job in the queue
     * 400 status_code if there is a job currently running or waiting
     *
     * @param teamName
     * @return response with the afforementioned status codes and the message
     * key containing the current job status
     *
     */
    public function checkJobStatus(Request $request) {
        $teamName = Session::get('team_name');

        $teamId = Team::where('teamName','=',$teamName)
                      ->pluck('id');

        $job = Submissions::where('teamId','=',$teamId)
                          ->orderBy('id','desc')
                          ->first();

        if($job) {
            $job_status = $job->status;
            $status_code = 200;
            $message = $job_status;
        } else {
            return JSONResponse::response(200, "NO SUBMISSIONS");
        }
        return JSONResponse::response($status_code,$message);
    }
    /**
     * Submit a zip file to the server and call the simulator
     *
     * @param teamId
     * @param teamName
     * @param file
     * @return \Illuminate\Http\Response
     */
    public function submitCode(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'file'     => 'required|mimes:zip',
            'level'    => 'required',
        ]);
        if ($validator->fails())
        {
            return view('done',[
                'text' => 'Invalid File'
            ]);
        }

        $file = $request->file('file');
        $team_name = Session::get('team_name');
        $team_id = Team::where('teamName','=',$team_name)
                       ->pluck('id');

        $current_time = Carbon::now();

        // Check how many submissions the user has made so far today
        $todays_submissions = Submissions::where('teamId','=',$team_id)
                            ->whereRaw('Date(created_at) = CURDATE()')
                            ->count();

        if($todays_submissions > 10) {
            return view('done',[
                'text' => 'Exceeded submission limit. Please try tomorrow'
            ]);
        };

        //Check for Valid Level No
        $level = $request->input('level');
        $currentLevelQuery = Team::where('id','=',$team_id)
                                 ->first();
        $currentLevel = $currentLevelQuery->currentLevel;

        // Checks if the contestant is competing for a proper level
        if($level == env('MAX_LEVEL') && $level != $currentLevel) {
            return view('done',[
                'text' => 'Incorrect Level'
            ]);
        }

        if(!($level == $currentLevel || $level == ($currentLevel-1))){
            return view('done',[
                'text' => 'Incorrect Level'
            ]);
        }

        // Check if the contestant already has a running submission
        $running_submissions = Submissions::where('teamId','=',$team_id)
                             ->where('status','=','RUNNING')
                             ->orWhere('status','=','WAITING')
                             ->get();
        if($running_submissions->count()) {
            return view('done',[
                'text' => 'Submissions already waiting or running'
            ]);
        }
        $ext = $file->getClientOriginalExtension();
        if(!$file->isValid()) {
            return view('done',[
                'text' => 'Invalid File'
            ]);
        }
        $team = Team::where('id','=',$team_id)
                               ->get();
        if($team->isEmpty()) {
            return view('done',[
                'text' => 'Invalid Team Id'
            ]);
        } else {
            $submission = Submissions::insert([
                    'teamId' => $team_id,
                    'levelNo'  => $request->input('level'),
                    'submittedCode' => file_get_contents($file->getRealPath()),
                    'created_at' => $current_time->toDateTimeString(),
                    'updated_at' => $current_time->toDateTimeString(),
            ]);
            return view('done',[
                'text' => 'Submission Successful'
            ]);
        }
    }
}
