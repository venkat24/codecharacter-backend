<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Jobs\simulation;
use App\Models\JobsStatus;
use Sangria\JSONResponse;
use Validator;
class SimulatorCall extends Controller
{
	public function callsimulator(Request $request)
	{
		$validator = Validator::make($request->all(),[
			'team_id' => 'required|integer'
		]);
		if ($validator->fails())
		{
			return JSONResponse::response(400,'Invalid parameters');
		}
		$team_id = $request->input('team_id');
		$teams = JobsStatus::where(['team_id','=',$team_id],
			                       ['status','=','Pending'])->first();
		if ($teams > 0)
		{
			$update_job_status = JobsStatus::where(['team_id','=',$team_id],
			                                       ['status','=','Pending'])->first();
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
}
?>