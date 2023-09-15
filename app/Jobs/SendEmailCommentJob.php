<?php

namespace App\Jobs;

use App\Mail\SendComment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailCommentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $application;
    public $comment;
    public $name;
    public function __construct($application, $comment)
    {
        $this->application = $application;
        $this->comment = $comment;
        $this->name = $application->name;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // dd($this->comment);
        Mail::to($this->application->email)->send(new SendComment($this->comment, $this->name));
    }
}
