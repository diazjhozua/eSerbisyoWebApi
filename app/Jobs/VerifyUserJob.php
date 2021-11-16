<?php

namespace App\Jobs;

use App\Mail\VerifyUserMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class VerifyUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $subject;
    protected $request;

    public function __construct(User $user, $subject, $request)
    {
        $this->user = $user;
        $this->subject = $subject;
        $this->request = $request;
    }

    public function handle()
    {
        Mail::to($this->user->email)
            ->send(new VerifyUserMail($this->subject, $this->request));
    }
}
