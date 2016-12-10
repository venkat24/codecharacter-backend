<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\JobsStatus;
use App\Http\Controllers\SimulatorCall;

class simulation extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $job_id;

    public function __construct($job_id)
    {
        $this->job_id = $job_id;
    }

    
    public function handle()
    {
        
        $job = JobsStatus::findorFail($this->job_id);
        $job->status = "Processing";
        $job->save();
        //run the simulation here
    }

    public function fail()
    {
      $job = JobsStatus::findorFail($this->job_id);
        $job->status = "Failed";
        $job->save();
    }
}
