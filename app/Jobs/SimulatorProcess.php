<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Submissions;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class SimulatorProcess extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $fileName;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($passedFileName)
    {
        $this->fileName = $passedFileName;  
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $command = 'sleep 10 && echo '.$this->fileName .' > TESTING.txt';
        exec($command);
        Submissions::where('fileName','=',$this->fileName)
                   ->update(['status'=>'ACCEPTED']);
    }
}
