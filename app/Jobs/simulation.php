<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class simulation extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $job_id;

    public function __construct()
    {
    //    $this->job_id = $job_id;
    }

    
    public function handle()
    {
        //run the simulation here
      //  console.log("hi there");
        \Log::info("something");
    }

    public function fail()
    {
      //to update the submissions table to timeout
    }
}
