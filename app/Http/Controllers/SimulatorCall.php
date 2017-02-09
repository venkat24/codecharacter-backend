<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Jobs\SimulatorProcess;
use Sangria\JSONResponse;
use Validator;

use Session;
use App\Job;
use App\Submissions;
use App\Team;

class SimulatorCall extends Controller
{
    public function callsimulator($fileName)
    {
        $this->dispatch(new SimulatorProcess($fileName));
    }

    /**
     * Returns the job status of the current team's task
     * 200 status_code if there is no job in the queue
     * 400 status_code if there is a job currently runniong or waiting
     * This route will not work unless the job status is being set 
     * in the SimulatorProcess Job
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
                          ->orderBy('created_at','desc')
                          ->first();

        if($job) {
            $job_status = $job->status;
            $status_code = 200;
            $message = "";
            if ($job_status == "ACCEPTED" || $job_status == "REJECTED") {
                $status_code = 200;
                $message = $job_status;
            } else {
                $status_code = 400;
                $message = $job_status;
            }
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
            'file'     => 'required',
        ]);
        if ($validator->fails())
        {
            $message = $validator->errors()->all();
            return JSONResponse::response(400,$message);
        }
        $file = $request->file('file');
        $team_name = Session::get('team_name');
        $team_id = Team::where('teamName','=',$team_name)
                       ->pluck('id');

        $ext = $file->getClientOriginalExtension();
        if(!$file->isValid()) {
            return JSONResponse::response(422,'Invalid file');
        }
        $team = Team::where('id','=',$team_id)
                               ->get();
        if($team->isEmpty()) {
            return JSONResponse::response(400,'Invalid team id');
        } else {
            $submission = Submissions::insert([
                    'teamId' => $team_id,
                    'levelNo'  => 0,
                    'submittedCode' => file_get_contents($file->getRealPath()),
            ]);
            $this->callSimulator($file->getRealPath());
            return JSONResponse::response(200, 'Upload successful');
        }
    }
}
