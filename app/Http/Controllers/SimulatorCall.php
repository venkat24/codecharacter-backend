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
use App\Team;

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
     * @param teamName
     * @return response with the afforementioned status codes and the message
     * key containing the current job status
     *
     */
    public function checkJobStatus(Request $request) {
        $validator = Validator::make($request->all(),[
            'teamName' => 'required|string'
        ]);
        if ($validator->fails()) {
            return JSONResponse::response(400,'Invalid parameters');
        }
        $teamName = $request->input('teamName'); 

        $teamId = Team::where('teamName','=',$teamName)
                      ->first()
                      ->pluck('id');

        $job = Submissions::where('teamId','=',$teamId)
                          ->orderBy('created_at','desc')
                          ->first();

        if($job) {
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
    public function submitCode(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'teamId' => 'required|integer',
            'teamName' => 'required|string',
            'file' => 'mimes:zip'
        ]);
        if ($validator->fails())
        {
            return JSONResponse::response(400,'Invalid parameters');
        }
        $team_name = $request->input('teamName');
        $team_id = $request->input('teamId');
        if($request->file('file')->isValid())
        {
            $filename = substr(md5(rand()), 0, 8)."_".$team_name;
            str_replace(" ", "_", $filename);
            $file = $request->file('file');
            $file->move(storage_path('submissions'), $filename);
        }
        else
        {
            return JSONResponse::response(422,'Invalid file');
        }
        $team = Team::where('teamId','=',$team_id)
                               ->get();
        if($team->isEmpty())
        {
            return JSONResponse::response(400,'Invalid team id');
        }
        else
        {
            $submission = Submissions::insert([
                    'teamId' => $team_id,
                    'levelNo'  => 0,
                    'sourceCodePath' => storage_path('submissions').$filename
            ]);
            return JSONResponse::response(200, 'Upload successful');
        }
    }
}
