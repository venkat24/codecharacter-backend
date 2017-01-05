<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Jobs\simulation;
use Sangria\JSONResponse;
use Validator;

use App\Jobs;
use App\Submissions;

class SimulatorCall extends Controller
{
    public function callsimulator(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'teamId' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return JSONResponse::response(400,'Invalid parameters');
        }
        $team_id = $request->input('team_id');
        $teams = Jobs::where(['team_id','=',$team_id],['status','=','Pending'])
                           ->first();
        if ($teams > 0) {
            $update_job_status = Jobs::where(['team_id','=',$team_id],['status','=','Pending'])
                                           ->first();
            $update_job_status->status = 'Cancelled';
            $update_job_status->save();
        }
        $job_status = new JobsStatus();
        $job_status->team_id = $team_id;
        $job_status->status = "Pending";
        $job_status->save();
        $this->dispatch(new Simulation); //Name of the job
        return JSONResponse::response(200, 'Successfully queued');
    }
    /**
     * Returns the job status of the current team's task
     * 200 status_code if there is no job in the queue
     * 400 status_code if there is a job currently runniong or waiting
     *
     * @param teamId
     * @return response with the afforementioned status codes and the message
     * key containing the current job status
     *
     */
    public function checkJobStatus(Request $request) {
        $validator = Validator::make($request->all(),[
            'teamId' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return JSONResponse::response(400,'Invalid parameters');
        }
        $teamId = $request->input('teamId'); 
        $job = Submissions::where('teamId','=',$teamId)
                               ->get();
        if($job->isEmpty()) {
            return JSONResponse::response(200, "No submissons made yet"); 
        } else {
            $status_code = 200;
            $message = "";
            $job_status = $job_status;
            if ($job_status == "ACCEPTED" || $job_status == "REJECTED") {
                $status_code = 200;
                $message = $job_status;
            } else {
                $status_code = 400;
                $message = $job_status;
            }
            switch($job_status) {
                case "WAITING" :
                    $status_code = 400;
                    $message = "WAITING";
                case "RUNNING" :
                    $status_code = 400;
                    $message = "RUNNING";
            }
        }
        return JSONResponse::response($status_code,$message);
    }
}

