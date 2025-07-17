<?php
namespace App\Jobs;

use App\Mail\ApplicationReceived;
use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendApplicationEmail implements ShouldQueue
{
    use Queueable;

    protected $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function handle()
    {
        $employerEmail = $this->application->vacancy->user->email;
        Mail::to($employerEmail)->send(new ApplicationReceived($this->application));
    }
}
